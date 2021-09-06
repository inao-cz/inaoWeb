<?php

namespace App\Controller;

use App\Entity\Invite;
use App\Entity\User;
use App\Form\InviteType;
use App\Util\EndpointUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
#[\Symfony\Component\Routing\Annotation\Route(path: '/admin', name: 'admin-')]
class AdminController extends AbstractController
{
    #[Route("/", name: "dashboard")]
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}