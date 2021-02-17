<?php

namespace App\DataFixtures;

use App\Entity\Cm;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CmFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 1; $i <= 3; $i++) {
            $user = new Cm();
            $profil = $this->getReference(ProfilFixtures::CM_REFERENCE);

            $user->setProfil($profil)
                ->setEmail($faker->email)
                ->setUsername('cm'.$i )
                ->setNomComplete($faker->name)
                ->setTelephone($faker->phoneNumber)
                ->setAdresse($faker->email)
                ->setStatus(false)
                ->setGenre($faker->randomElement(["male", "female"]));

            //Génération des User
            $password = $this->encoder->encodePassword($user, 'pass1234');
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
