<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DocuController
 * @package App\Controller
 *
 * @Route("/docu", name="controller-docu")
 */
class DocuController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(){
        return $this->renderView("");
    }
}