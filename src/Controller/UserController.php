<?php
namespace App\Controller;

use App\Entity\Log;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user", name="user-")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class UserController extends AbstractController{

    /**
     * @Route("/", name="index")
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('user-dashboard-index');
        }
        return $this->redirectToRoute('login');
    }

    /**
     * @Route("/dash/", name="dashboard-index")
     */
    public function dashboardAction(): Response
    {
        $history = $this->getDoctrine()->getRepository(Log::class)->getHistory($this->getUser()->getApiKey());
        $parsedHistory = [];
        /** @var Log $item */
        foreach ($history as $key => $item){
            $parsedHistory[$key]['date'] = $item->getDate();
            $parsedHistory[$key]['action'] = $item->getAction();
        }
        return $this->render('user/dashboard.html.twig', [
            'username' => $this->getUser()->getUsername(),
            'activities' => $parsedHistory,
            'apiKey' => $this->getUser()->getApiKey()->getApiKey()
        ]);
    }
}