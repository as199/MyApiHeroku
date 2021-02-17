<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture implements DependentFixtureInterface
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
            $user = new Admin();
            $profil = $this->getReference(ProfilFixtures::ADMIN_REFERENCE);

            $user->setProfil($profil)
                ->setEmail($faker->email)
                ->setUsername('admin'.$i )
                ->setNomComplete($faker->name)
                ->setTelephone($faker->phoneNumber)
                ->setAdresse($faker->email)
                ->setStatus(false)
                ->setGenre($faker->randomElement(["male", "female"]))
                ->setAvartar($faker->imageUrl($width = 640, $height = 480));

            //Génération des User
            $password = $this->encoder->encodePassword($user, 'pass1234');
            $user->setPassword($password);

            $manager->persist($user);
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return array(
            ProfilFixtures::class,
        );
    }
}
