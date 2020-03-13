<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Gestion est affichage of connect formular
     * 
     * @Route("/login", name="account_login")
     * 
     *@return Response
     */
    
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username

        ]);
    }

/**
 * Disconnect function 
 * @Route("/logout", name="account_logout")
 *
 * @return void
 */
    public function logout()
    {
        // Jack shit
    }
/**
 * Register function
 *@Route("/register", name="account_register")
 * 
 * @return Response
 */
    public function FunctionName()
    {
         $user = new User();

         $form = $this->createForm(RegistrationType::class, $user);

         return $this->render('account/registration.html.twig', [
             'form' => $form->createView()
         ]);
    }
}
