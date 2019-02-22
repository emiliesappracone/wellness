<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecurityController extends AbstractController implements AccessDeniedHandlerInterface
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Request $request)
    {
        $lastUsername = $authenticationUtils->getLastUsername();
        $errors = $authenticationUtils->getLastAuthenticationError();
        return $this->render('front/security/login.html.twig', [
            'lastUsername' => $lastUsername,
            'errors' => $errors,
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render('front/front.html.twig', [
        ]);
    }

    /**
     * Method simple redirect to home page
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
//        dump($accessDeniedException); die();
//        $errors = 'Vous n\'êtes pas autorisé sur cette page.';
        // Simple redirection
        if($accessDeniedException){
            return $this->redirectToRoute('home');
        }
    }
}
