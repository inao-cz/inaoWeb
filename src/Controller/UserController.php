<?php

namespace App\Controller;

use App\Entity\ApiKey;
use App\Entity\Invite;
use App\Entity\Log;
use App\Form\InviteType;
use App\Util\EndpointUtil;
use App\Util\MailUtil;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 */
#[Route(path: '/user', name: 'user-'), IsGranted("IS_AUTHENTICATED_FULLY")]
class UserController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route(path: '/', name: 'index')]
    public function index() : RedirectResponse
    {
        return $this->redirectToRoute('user-dashboard-index');
    }

    #[Route(path: '/dash/', name: 'dashboard-index')]
    public function dashboardAction() : Response
    {
        $history = $this->getDoctrine()->getRepository(Log::class)->getHistory($this->getUser()->getApiKey());
        $parsedHistory = [];
        /** @var Log $item */
        foreach ($history as $key => $item) {
            $parsedHistory[$key]['date'] = $item->getDate();
            $parsedHistory[$key]['action'] = $item->getAction();
        }
        return $this->render('user/dashboard.html.twig', [
            'username' => $this->getUser()->getUsername(),
            'activities' => $parsedHistory,
            'apiKey' => $this->getUser()->getApiKey()->getApiKey()
        ]);
    }
    /**
     * @return Response
     */
    #[Route(path: '/invite/', name: 'invite'), IsGranted("ROLE_INVITE")]
    public function inviteUserAction(Request $request, EndpointUtil $endpointUtil, MailUtil $mailUtil)
    {
        $invite = new Invite();
        $invite->setCode($endpointUtil->randomString())->setValidUntil((new DateTime('+12 hours')));
        $form = $this->createForm(InviteType::class, $invite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $invite->setRoles($form->get('groups')->getData());
            } else {
                $invite->setRoles(['ROLE_USER']);
            }
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($invite);
            $manager->flush();
            $mailUtil->sendEmail('register', 'Invited to inao.xn--6frz82g', $invite->getEmail(), $this->generateUrl('register', ['invite' => $invite->getCode()]));
        }
        return $this->render('admin/invite.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @IsGranted("ROLE_IMAGES")
     */
    #[Route(path: '/image/config', name: 'image-config')]
    public function viewImageConfig()
    {
        /** @var ApiKey $apiKey */
        $apiKey = $this->getUser()->getApiKey();
        return $this->render('image/config.html.twig', ['config' => '{"Version":"13.1.0","Name":"inaoImageService","DestinationType":"ImageUploader","RequestMethod":"POST","RequestURL":"https://inao.xn--6frz82g/endpoint/image","Body":"MultipartFormData","Arguments":{"key": "' . $apiKey->getApiKey() . '"},"FileFormName": "sharex","URL": "https://inao.xn--6frz82g$response$"}']);
    }

    #[Route(path: '/image/list', name: 'image-list'), IsGranted("ROLE_IMAGES")]
    public function viewImagesList(){

        return $this->render('image/list.html.twig');
    }

    /**
     * @return ManagerRegistry
     */
    public function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }
}