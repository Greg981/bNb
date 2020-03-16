<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfiguration("Old Password", 
            "Enter your actual Password" ))
            ->add('newPassword', PasswordType::class, $this->getConfiguration("New Password",
            "Enter your new Password ..."))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration("Confirm New Password",
            "Confirm your new password ..."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
