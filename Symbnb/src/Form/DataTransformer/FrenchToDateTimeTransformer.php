<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface{

    public function transform($date)
    {
        if($date === null){
            return '';
        }

        return $date->format('d/m/Y');
    }

    public function reverseTranform($frenchDate)
    {
        // frenchDate = 21/03/2020
        if ($frenchDate === null) {
            // exception
            throw new TransformationFailedException("You need to provide a date !");
        }

        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate);

        if ($date === false) {
            //Exception
            throw new TransformationFailedException("Invalid date format !");
        }
        return $date;
    }
}