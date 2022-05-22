<?php

namespace App\Controller;

use App\Entity\QrcodeUser;
use App\Form\QrCodeAdminType;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route(path: '/qr', name: 'controller-qr')]
class QrcodeAdminController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route(path: '/', name: '-index')]
    public function index()
    {
        if($this->isGranted("IS_AUTHENTICATED_FULLY") && $this->getUser() instanceof QrcodeUser){
            return $this->redirectToRoute('controller-qr-admin');
        }
        return $this->redirectToRoute('controller-qr-login');
    }

    #[Route(path: '/admin', name: "-admin")]
    #[IsGranted('ROLE_QR')]
    public function adminIndex(Request $request)
    {
        if(!$this->isGranted('ROLE_QR')){
            return $this->redirectToRoute('controller-qr-login');
        }
        $user = $this->getUser();
        assert($user instanceof QrcodeUser);
        assert($user->getRedirect() !== null);
        $form = $this->createForm(QrCodeAdminType::class, $user->getRedirect());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($form->getData());
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('qrcode/admin.html.twig', ['redForm' => $form->createView(),
            'redirects' => $user->getRedirect()->getRedirects(),
            'redUrl' => $request->getSchemeAndHttpHost() . $this->generateUrl('links-go', ['id' => $user->getRedirect()->getName()])
        ]);
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

    /**
     * @return ManagerRegistry
     */
    public function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }
}