<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("FirstName", "Enter your First Name..."))
            ->add('lastName', TextType::class, $this->getConfiguration("LastName", "Enter your Last Name..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Enter your Email adress..."))
            ->add('picture', UrlType::class, $this->getConfiguration("Avatar", "Enter your Avatar Url..."))
            ->add('hash', PasswordType::class, $this->getConfiguration("Password", "Enter your Password..."))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirm Password","Confirm Your Password"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Please Introduce Yourself in few words..."))
            ->add('description', TextareaType::class, $this->getConfiguration("Detailled Description", "Please present yourself in a detailled manner"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
