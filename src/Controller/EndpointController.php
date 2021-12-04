<?php

namespace App\Controller;

use App\Entity\ApiKey;
use App\Entity\Captcha;
use App\Entity\Image;
use App\Entity\Log;
use App\Util\EndpointUtil;
use DateTime;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/endpoint/", name: "endpoint-")]
class EndpointController extends AbstractController
{
    #[Route('image', name: "image", methods: ["GET","POST"])]
    public function imgEndpoint(Request $request, EndpointUtil $endpointUtil): Response
    {
        $key = $request->request->get('key');
        if (!$key) {
            return $endpointUtil->getUnauthorizedResponse();
        }

        $user = $this->getDoctrine()->getRepository(ApiKey::class)->findOneBy([
            'apiKey' => $key
        ])->getUser();

        if (!$user) {
            return $endpointUtil->getUnauthorizedResponse();
        }


        $filesystem = new Filesystem();

        $filename = $endpointUtil->randomString();
        /** @var File $target */
        $target = $request->files->get('sharex');
        $type = $target->guessExtension();
        $date = new DateTime('now');
        $sharexdir = '../img/www/' . $date->format("Y/m/") . $user->getUsername() . "/";

        if (!$filesystem->exists($sharexdir . "..")) {
            $filesystem->mkdir($sharexdir . "..", 0755);
        }
        if (!$filesystem->exists($sharexdir)) {
            $filesystem->mkdir($sharexdir, 0755);
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

    #[Route("captcha/", name: "captcha-create", methods: ["POST"])]
    public function captchaEndpoint(Request $request, EndpointUtil $endpointUtil): Response
    {
        $requestJson = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $response = [];
        if ($requestJson['auth'] === $this->getParameter('app.bot_endpoint_auth')) {
            $captchaArray = [];
            foreach ($requestJson['discordId'] as $id) {
                $captcha = $this->getDoctrine()->getRepository(Captcha::class)->findOneBy([
                    'discordId' => $requestJson['discordId'][0]
                ]);
                if($captcha === null){
                    $captcha = new Captcha();
                }
                $response[$captcha->getDiscordId()] = $captcha->getCaptchaId();
                $captcha->setDiscordId($id);
                $captcha->setCaptchaId($endpointUtil->randomString());
                $response[$captcha->getDiscordId()] = $captcha->getCaptchaId();
                $captchaArray[] = $captcha;
            }
            if (count($captchaArray) > 0) {
                $em = $this->getDoctrine()->getManager();
                foreach ($captchaArray as $item) {
                    $em->persist($item);
                }
                $em->flush();
            }
        } else {
            return $endpointUtil->getUnauthorizedResponse();
        }

        return $this->render("empty.html.twig", [
            'content' => json_encode($response, JSON_THROW_ON_ERROR)
        ]);
    }

    #[Route("captchacheck/", name: "captcha-check", methods: ["POST"])]
    public function captchaCheckEndpoint(Request $request, EndpointUtil $endpointUtil): Response
    {
        $requestJson = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $response = [];
        if ($requestJson['auth'] === $this->getParameter('app.bot_endpoint_auth')) {
            $captcha = $this->getDoctrine()->getRepository(Captcha::class)->findOneBy([
                'discordId' => $requestJson['discordId'][0]
            ]);
            $response[$requestJson['discordId'][0]] = $captcha?->isCaptcha() ?? false;
        } else {
            return $endpointUtil->getUnauthorizedResponse();
        }

        return $this->render("empty.html.twig", [
            'content' => json_encode($response, JSON_THROW_ON_ERROR)
        ]);
    }

    /**
     * @throws JsonException
     */
    #[Route("captchadelete/", name: "captcha-delete", methods: ["POST"])]
    public function captchaDeleteEndpoint(Request $request, EndpointUtil $endpointUtil): Response
    {
        $requestJson = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if ($requestJson['auth'] === $this->getParameter('app.bot_endpoint_auth')) {
            $captcha = $this->getDoctrine()->getRepository(Captcha::class)->findOneBy([
                'discordId' => $requestJson['discordId'][0]
            ]);
            if ($captcha !== null) {
                $this->getDoctrine()->getManager()->remove($captcha);
                $this->getDoctrine()->getManager()->flush();
            }
        } else {
            return $endpointUtil->getUnauthorizedResponse();
        }

        return $this->render("empty.html.twig");
    }

    #[Route("prvnt/{timestamp}", name: "prvnt-update", methods: ["GET"])]
    public function prvntUpdate(Request $request, $timestamp = null){
        if($timestamp === null){

        }
    }
}