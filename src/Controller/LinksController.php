<?php
namespace App\Controller;

use App\Entity\Links;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LinksController
 * @package App\Controller
 */
#[Route(path: '/link', name: 'links-')]
class LinksController extends AbstractController
{
    #[Route(path: '/', name: 'index')]
    public function index() : Response
    {
        $doc = $this->getDoctrine()->getRepository(Links::class)->findBy([
            'public' => 1
        ]);
        return $this->render('links/index.html.twig', ['links' => $doc]);
    }
    /**
     * @param $id
     */
    #[Route(path: '/go/{id}', name: 'go')]
    public function redirectToLink($id = "") : Response
    {
        if(empty($id)){
            return $this->render('links/redirect.html.twig', ['message' => "No ID was provided. And because I'm not an magician, I cannot do anything :)", 'script' => ""]);
        }
        $doc = $this->getDoctrine();
        $results = $doc->getRepository(Links::class)->findOneBy([
            'name' => $id
        ]);
        if(is_null($results)){
            return $this->render('links/redirect.html.twig', ['message'=>"Invalid ID provided. :(", 'script' => ""]);
        }
        $script = 'window.onload = (function(){
               setTimeout(function () {
                window.location.replace("' . $results->getTarget() . '")
               }, 3000)
        });';
        return $this->render('links/redirect.html.twig', ['message' => "Redirecting to location.", 'script' => base64_encode($script)]);
    }
}