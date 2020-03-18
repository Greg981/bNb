<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\ApplicationType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, $this->getConfiguration("Check-in date", 
            "enter your Check-in date", ["widget" => "single_text"]))
            ->add('endDate', DateType::class, $this->getConfiguration("Check-out date", 
            "Enter your Check-out date", ["widget" => "single_text"]))
            ->add('comment', TextareaType::class, $this->getConfiguration(false,
            "If you have a comment, please let us know! "))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
