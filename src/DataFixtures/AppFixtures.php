<?php

namespace App\DataFixtures;

use App\Entity\Publication;
use App\Entity\PublicationLike;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;


class AppFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $publication = new Publication();
            $publication->setTitre($faker->sentence(6))               
                ->setText('<p>' . join(',', $faker->paragraphs()) . '</p>');

            $manager->persist($publication);

            for ($j = 0; $j < mt_rand(0, 10); $j++) {
                $like = new PublicationLike();
                $like>setPublication($publication);

                $manager->persist($like);
            }
        }

        $manager->flush();
    }
}