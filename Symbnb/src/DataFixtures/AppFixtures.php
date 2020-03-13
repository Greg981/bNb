<?php

namespace App\DataFixtures;

use\App\Entity\Ad;
use\App\Entity\Pics;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AppFixtures extends Fixture
{
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        // gestions des users
        $users = [];
        // gestions des sexes
        $genres = ['male', 'female'];


        for ($j=1; $j <=15; $j++) { 
           $user = new User();

           // generation de faux sexe pour avatar and firstName
           $genre = $faker->randomElement($genres);

           $picture = 'https://randomuser.me/api/portraits/';
           $pictureId = $faker->numberBetween(1, 99) . 'jpg';

           $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;

           $hash =$this->encoder->encodePassword($user, 'password');

           $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

                $manager->persist($user);
                $users[] = $user;
        }

        // gestions des annonces
        for ($i=1; $i <= 30 ; $i++) 
        { 
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageURL(1000,350);
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0, count($users) -1)];

            $ad->setTitle($title)
                ->setCoverImage("$coverImage")
                ->setIntroduction("$introduction")
                ->setContent("$content")
                ->setPrice(mt_rand(37, 280))
                ->setRooms(mt_rand(1, 5))
                ->setAuthor($user);

            for ($j=1; $j <= mt_rand(2, 5); $j++) 
            { 
                $pics = new Pics();

                $pics->setUrl($faker->imageUrl())
                     ->setCaption($faker->sentence())
                     ->setAd($ad);

                $manager->persist($pics);     
            }        

            $manager->persist($ad);
        }

        $manager->flush();
    }
}
