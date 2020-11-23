<?php

namespace App\Controller;

use App\Form\DecryptType;
use App\Mapper\Cryptic;
use App\Util\CrypticService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CrypticController
 * @package App\Controller
 * @Route("/cryptic", name="cryptic-")
 */
class CrypticController extends AbstractController
{
    /**
     * @Route("/", name="decrypt")
     * @param Request $request
     * @return Response
     */
    public function decrypt(Request $request): Response
    {
        $data = new Cryptic();

        $form = $this->createForm(DecryptType::class, $data);
        $form->handleRequest($request);

        $result = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $crypticService = new CrypticService();
            $result = $crypticService->decrypt($data);
        }

        return $this->render('cryptic/decrypt.html.twig', [
            'form' => $form->createView(),
            'result' => $result
        ]);
    }
}