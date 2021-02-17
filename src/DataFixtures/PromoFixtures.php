<?php

namespace App\DataFixtures;

use App\Entity\Promo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PromoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 0; $i < 5; $i++) {
            $promo = new Promo();
            $promo->setTitre($faker->title)
                ->setLangue($faker->randomElement(['FR', 'EN']))
                ->setAvatar($faker->imageUrl(640, 480))
                ->setDescription($faker->text)
                ->setDateFinProvisoire($faker->dateTime)
                ->setDateFinReel($faker->dateTime)
                ->setFabrique('SONATEL ACDEMY')
                ->setLieu($faker->city)
                ->addReferenciel($this->getReference(ReferentielsFixtures::REF.$i));

            $manager->persist($promo);

        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.
        return [ReferentielsFixtures::class];
    }
}
