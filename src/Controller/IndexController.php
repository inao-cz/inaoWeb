<?php

namespace App\Controller;

use App\Entity\Image;
use App\Util\EndpointUtil;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route(path: '/', name: 'index')]
    public function index() : Response
    {
        return $this->render('homepage/index.html.twig');
    }

    /**
     * @param EndpointUtil $util
     * @param null $image
     * @return Response
     */
    #[Route(path: '/image/{image}', name: 'index-image-render')]
    public function renderImageAction(EndpointUtil $util, $image = null): Response
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
        $dataType = $util->getDataType($image->getExt());
        $url = "https://img.inao.xn--6frz82g/" . $image->getUploaded()->format("Y/m/") . $image->getUser()->getUsername() . "/" . $image->getName() . "." . $image->getExt();
        return $this->render('image/view.html.twig', ['data' => $url, 'type' => $dataType]);
    }

    /**
     * @return ManagerRegistry
     */
    public function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }
}