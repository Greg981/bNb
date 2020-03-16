<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function register(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
    {
         $user = new User();

         $form = $this->createForm(RegistrationType::class, $user);

         $form->handleRequest($request);

         if($form->isSubmitted() && $form->isValid()) 
         {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',"Your account have been successfully created ; you can now login!"
            );
            return $this->redirectToRoute('account_login');
         }

         return $this->render('account/registration.html.twig', [
             'form' => $form->createView()
         ]);
    }

    /**
     * Edit Profile function
     * 
     * @Route("/account/profile", name="account_profile")
     *
     * @return Response
     */
    public function profile(Request $request, EntityManagerInterface $manager) {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success', "Your modification have been successfully saved !"
            );
        }

       return $this->render('account/profile.html.twig', [
            'form' => $form->createView()

       ]);
    }

        /**
         * Modify Password Function
         * 
         *@Route("/account/password-update", name="account_password")

         * @return Response
         */
        public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
        {
            $passwordUpdate = new PasswordUpdate();

            $user = $this->getUser();

            $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //  verify that oldPassword du form is the same as user password
                if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                    // Gérer l'erreur
                    $form->get('oldPassword')->addError(new FormError("The password you've type in do not match with your actual Password "));
                }else{
                    $newPassword = $passwordUpdate->getNewPassword();
                    $hash = $encoder->encodePassword($user, $newPassword);
    
                    $user->setHash($hash);
    
                    $manager->persist($user);
                    $manager->flush();
    
                    $this->addFlash(
                        'success',
                        "Your Password have succesfully modified!!"
                    );
                    return $this->redirectToRoute('home');
                }
            }

            return $this->render('account/password.html.twig', [
                'form' => $form->createView()
            ]);
        }
}
