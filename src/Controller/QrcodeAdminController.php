<?php

namespace App\Controller;

use App\Entity\QrcodeUser;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route(path: '/qr', name: 'controller-qr')]
class QrcodeAdminController extends AbstractController
{

    #[Route(path: '/', name: '-index')]
    public function index()
    {
        if($this->isGranted("IS_AUTHENTICATED_FULLY") && $this->getUser() instanceof QrcodeUser){
            return $this->redirectToRoute('controller-qr-admin');
        }
        return $this->redirectToRoute('controller-qr-login');
    }

    #[Route(path: '/admin', name: "-admin")]
    public function adminIndex()
    {

        return $this->render('qrcode/admin.html.twig');
    }

    #[Route(path: '/login', name: '-login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->isGranted("IS_AUTHENTICATED_FULLY") && $this->getUser() instanceof QrcodeUser){
            return $this->redirectToRoute("controller-qr-admin");
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security_qr/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: '-logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}