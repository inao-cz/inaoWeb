<?php

namespace App\Controller;

use App\Entity\Invite;
use App\Form\InviteType;
use App\Util\EndpointUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
#[Route(path: '/admin', name: 'admin-')]
class AdminController extends AbstractController
{
    #[Route("/", name: "dashboard")]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route("/invite/", name: "invite")]
    public function inviteCreate(Request $request, EndpointUtil $endpointUtil): Response
    {
        $invite = new Invite();
        $invite->setCode($endpointUtil->randomString());
        $form = $this->createForm(InviteType::class, $invite);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $invite->setRoles(array_values($form->get('groups')->getData()));
        }

        return $this->renderForm('admin/invite.html.twig', ['form' => $form]);
    }

    public function qruserCreate(Request $request): Response
    {

    }
}