<?php

namespace App\Controller;

use App\Entity\Image;
use App\Util\MailUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('homepage/index.html.twig');
    }

    /**
     * @Route("/image/render/{image}", name="index-image-render")
     * @param $image
     * @return Response
     */
    public function renderImageAction($image = null)
    {
        if(!$image){
            exit();
        }
        $image = $this->getDoctrine()->getRepository(Image::class)->findOneBy(['name' => $image]);
        if(!$image) {
            exit();
        }
        $filesystem = new Filesystem();
        $path = "../images/user/" . $image->getUser()->getUsername() . "/" . $image->getName() . "." . $image->getExt();
        if(!($filesystem->exists($path))){
            $this->getDoctrine()->getManager()->remove($image);
            $this->getDoctrine()->getManager()->flush();
            exit("Image doesn't exist");
        }
        return $this->render('image/view.html.twig', ['image' => "data:" . $image->getExt() . ";base64," . base64_encode(file_get_contents($path))]);
    }

    /**
     * @Route("/test", name="test")
     * @param MailUtil $util
     * @return Response
     */
    public function test(MailUtil $util): Response
    {
        $test = $util->sendEmail('register', 'Registration on website', 'onemoreplayscz@gmail.com', "https://test.com");
        return $this->redirectToRoute('index');
    }
}