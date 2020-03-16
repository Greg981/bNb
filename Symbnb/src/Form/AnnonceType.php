<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AnnonceType extends ApplicationType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Ad Title", "Wrote a wonderfull title for your Ad !!"))
            ->add('slug', TextType::class, $this->getConfiguration("Slug", "Enter the url (leave blank if in doubt)", ['required' => false ]))
            ->add('coverImage', UrlType::class, $this->getConfiguration("Main Picture URL", "Enter the main picture url"))
            ->add('introduction',TextType::class, $this->getConfiguration("Description", "Give me dream Description"))
            ->add('content', TextareaType::class, $this->getConfiguration("Fully detail description", "Now start make me dreaming with your detailled content.... "))
            ->add('rooms',IntegerType::class, $this->getConfiguration("Rooms", "How many room available ?"))
            ->add('price', MoneyType::class, $this->getConfiguration("Price Per Night", "Enter your price "))
            ->add('pics', CollectionType::class,
                    [
                        'entry_type' => ImageType::class,
                        'allow_add' => true,
                        'allow_delete' => true ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
