<?php

namespace App\Controller;

use App\Entity\ApiKey;
use App\Entity\Captcha;
use App\Entity\Invite;
use App\Entity\User;
use App\Form\RecaptchaType;
use App\Form\RegistrationType;
use App\Util\UserUtil;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils) : Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('index');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security_user/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout() : void
    {
    }

    /**
     * @param $invite
     * @throws Exception
     */
    #[Route(path: '/register/{invite}', name: 'register')]
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserUtil $util, $invite = null) : Response
    {
        if(!$invite){
            return $this->redirectToRoute('index');
        }
        $invite = $this->getDoctrine()->getRepository(Invite::class)->findOneBy(['code' => $invite]);
        if($invite === null){
            return $this->redirectToRoute('index');
        }
        if($invite->getUsedBy() !== null){
            return $this->redirectToRoute('index');
        }
        if($invite->getValidUntil()->getTimestamp() < (new DateTime('now'))->getTimestamp()){
            $this->getDoctrine()->getManager()->remove($invite);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('index');
        }
        if($this->getUser()){
            return $this->redirectToRoute('index');
        }
        $user = new User();
        $user->setEmail($invite->getEmail());
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $user->setRoles($invite->getRoles());
            $apiKey = new ApiKey();
            $apiKey->setUser($user)->setApiKey($util->getRandomApiKey());
            $invite->setUsedBy($user);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->persist($apiKey);
            $this->getDoctrine()->getManager()->persist($invite);
            $this->getDoctrine()->getManager()->flush();

            return $this->render('homepage/index.html.twig');
        }
        return $this->render('user/register.html.twig', [
            'reg' => $form->createView()
        ]);
    }
    #[Route(path: '/captcha/{id}', name: 'security-captcha')]
    public function onCaptchaRequest(Request $request, $id)
    {
        $captcha = $this->getDoctrine()->getRepository(Captcha::class)->findOneBy(['captchaId' => $id]);
        if($captcha === null) {
            return $this->redirectToRoute('index');
        }
        $form = $this->createForm(RecaptchaType::class, $captcha);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $captcha->setCaptcha(true);
            $this->getDoctrine()->getManager()->persist($captcha);
            $this->getDoctrine()->getManager()->flush();

        }
        return $this->render('security_user/captcha.html.twig', [
            'form' => $form->createView(),
            'solved' => $captcha->isCaptcha()
        ]);
    }

    /**
     * @return ManagerRegistry
     */
    public function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }
}
