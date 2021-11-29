<?php

namespace App\Controller;

use App\Entity\Image;
use App\Util\MailUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index() : Response
    {
        return $this->render('homepage/index.html.twig');
    }

    /**
     * @param $image
     * @return Response
     */
    #[Route(path: '/image/{image}', name: 'index-image-render')]
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
        $dataType = $this->getDataType($image->getExt());
        $url = "https://img.inao.xn--6frz82g/" . $image->getUploaded()->format("Y/m/") . $image->getUser()->getUsername() . "/" . $image->getName() . "." . $image->getExt();
        return $this->render('image/view.html.twig', ['data' => $url, 'type' => $dataType]);
    }

    private function getDataType(string $ext){
        $extensions = [
            'image' => ["jpg", "jpeg", "jfif", "jfif", "pjpeg", "pjp", "mpeg", "png", "gif"],
            'text' => ["txt", "php", "html", "csv", "css", "xml"]
        ];
        foreach($extensions as $key => $row){
            if(in_array($ext, $row)){
                return $key;
            }
        }
        return "image";
    }
}