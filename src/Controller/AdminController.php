<?php

namespace App\Controller;

use App\Entity\Invite;
use App\Entity\Links;
use App\Entity\QrcodeUser;
use App\Form\InviteType;
use App\Form\QrCodeUserType;
use App\Util\EndpointUtil;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;


/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
#[Route(path: '/admin', name: 'admin-')]
class AdminController extends AbstractController
{
    private ManagerRegistry $registry;
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

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

    #[Route("/qr/", name: "qr")]
    public function qruserCreate(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $qrcodeuser = new QrcodeUser();
        $redir = new Links();
        if($request->isMethod('GET')){
            $qrcodeuser->setUuid(Uuid::v4());
        }else{
            $reqData = $request->request->get('qr_code_user');
            $qrcodeuser->setUuid($reqData['uuid']);
            $qrcodeuser->setPassword($reqData['password']);
        }

        $redir->setName("qr_" . $qrcodeuser->getUuid());
        $redir->setCreator($redir->getName());
        $redir->setPublic(false);
        $redir->setRedirects(0);
        $redir->setTarget("https://google.com");

        $form = $this->createForm(QrCodeUserType::class, $qrcodeuser);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->getDoctrine()->getManager()->persist($redir);
            $qrcodeuser->setPassword($hasher->hashPassword($qrcodeuser, $qrcodeuser->getPassword()));
            $qrcodeuser->setRedirect($redir);
            $this->getDoctrine()->getManager()->persist($qrcodeuser);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render("admin/qr.html.twig", ['qrform' => $form->createView()]);
    }

    public function getDoctrine(): ManagerRegistry
    {
        return $this->registry;
    }
}