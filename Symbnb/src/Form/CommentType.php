<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class, $this->getConfiguration("Score on 5",
            "Please give a score between 0 to 5", [
                'attr'  => [
                    'min'   => 0,
                    'max'   => 5,
                    'step'  => 1
                ]
            ]))
            ->add('content' , TextareaType::class, $this->getConfiguration("Your opinion /
             feedback", "Be as detailled as possible this will help other traveller !"))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
