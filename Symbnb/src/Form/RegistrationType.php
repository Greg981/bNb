<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    /**
    * base config for a field in the form
    *
    * @param string $label
    * @param string $placeholder
    * @param array $options
    * @return array
    */
    private function getConfiguration($label, $placeholder, $options = [])
    { return array_merge([
            'label' => $label,
                'attr' => [
                'placeholder' => $placeholder
                ]
                
                ], $options);    
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("FirstName", "Enter your First Name..."))
            ->add('lastName', TextType::class, $this->getConfiguration("LastName", "Enter your Last Name..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Enter your Email adress..."))
            ->add('picture', UrlType::class,$this->getConfiguration("Avatar", "Enter your Avatar Url..."))
            ->add('hash', PasswordType::class,$this->getConfiguration("Password", "Enter your Password..."))
            ->add('introduction', TextType::class,$this->getConfiguration("Introduction", "Please Introduce Yourself in few words..."))
            ->add('description', TextareaType::class,$this->getConfiguration("Detailled Description", "Please present yourself in a detailled manner"))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
