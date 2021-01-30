<?php

namespace App\Controller;

use App\Entity\ApiKey;
use App\Entity\Image;
use App\Entity\Log;
use App\Util\EndpointUtil;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EndpointController
 * @package App\Controller
 *
 * @Route("/endpoint/", name="endpoint-")
 */
class EndpointController extends AbstractController
{
    /**
     * @Route("image", methods={"GET","POST"}, name="image")
     * @param Request $request
     * @param EndpointUtil $endpointUtil
     */
    public function imgEndpoint(Request $request, EndpointUtil $endpointUtil): void
    {
        $key = $request->request->get('key');
        if (!$key) {
            exit('-2');
        }
        $user = $this->getDoctrine()->getRepository(ApiKey::class)->findOneBy([
            'apiKey' => $key
        ])->getUser();
        if (!$user) {
            exit('-1');
        }

        $filesystem = new Filesystem();

        $filename = $endpointUtil->randomString();
        /** @var File $target */
        $target = $request->files->get('sharex');
        $type = $target->guessExtension();
        $date = new DateTime('now');
        $sharexdir = '../img/www/' . $date->format("Y/m/") . $user->getUsername() . "/";

        if (!$filesystem->exists($sharexdir)) {
            $filesystem->mkdir($sharexdir, 755);
        }
        $filesystem->copy($target, $sharexdir . $filename . "." . $type, true);

        $log = new Log();
        $log->setAction("Uploaded new image file.")->setApiKey($user->getApikey())->setDate(new DateTime());
        $image = new Image();
        $image->setUser($user);
        $image->setName($filename)->setExt($type);
        $image->setUploaded($date);
        $dm = $this->getDoctrine()->getManager();
        $dm->persist($image);
        $dm->persist($log);
        $dm->flush();
        exit($this->generateUrl('index-image-render', ['image' => $image->getName()]));
    }

    /**
     * @Route("captcha/", name="captcha-create", methods={"POST"})
     * @param Request $request
     */
    public function captchaEndpoint(Request $request)
    {
        $json = $request->get('request');
        if ($json === null) {
            exit("-1");
        }
        $json = json_decode($json);
    }
}