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
     * @Route("/image/{image}", name="index-image-render")
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
        $path = "../img/www/" . $image->getUploaded()->format("Y/m/") . $image->getUser()->getUsername() . "/" . $image->getName() . "." . $image->getExt();
        if(!($filesystem->exists($path))){
            $this->getDoctrine()->getManager()->remove($image);
            $this->getDoctrine()->getManager()->flush();
            exit("Image doesn't exist");
        }
        $url = "https://img.inao.xn--6frz82g/" . $image->getUploaded()->format("Y/m/") . $image->getUser()->getUsername() . "/" . $image->getName() . "." . $image->getExt();
        return $this->render('image/view.html.twig', ['image' => $url]);
    }
    
    public function test(MailUtil $util): Response
    {
        //$test = $util->sendEmail('register', 'Registration on website', 'onemoreplayscz@gmail.com', "https://test.com");
        return $this->redirectToRoute('index');
    }
}