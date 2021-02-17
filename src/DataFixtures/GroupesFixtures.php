<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class GroupesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $groupe = new Groupe();
            $groupe->setNomGroupe($faker->randomElement(['G1', 'G2', 'G3', 'G4', 'G5']))
                ->setDateCreation($faker->dateTime)
                ->setStatus($faker->randomElement([true, false]))
                ->setType($faker->randomElement(['principal', 'secondaire']));
            $manager->persist($groupe);
        }




        $manager->flush();
    }
}
