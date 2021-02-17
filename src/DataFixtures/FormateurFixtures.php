<?php

namespace App\DataFixtures;

use App\Entity\Brief;
use App\Entity\Formateur;
use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormateurFixtures extends Fixture  implements DependentFixtureInterface
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
            $brief = new Brief();
            //$tag = new Tag();
            $brief->setLangue($faker->randomElement(['FR', 'EN']))
                ->setDescription($faker->text)
                ->setCritereEvaluation($faker->text)
                ->setCreatedAt($faker->dateTime)
                ->setAvatar($faker->imageUrl(640, 480))
                ->setModalitePedagogique($faker->text)
                ->setNomBrief($faker->title)
                ->setModalitesEvaluation($faker->realText())
                ->setStatusBrief($faker->randomElement(['assigné', 'non assigné']))
                ->setEtat($faker->randomElement(['brouillon', 'valide']));
            $user = new Formateur();
            $profil = $this->getReference(ProfilFixtures::FORMATEUR_REFERENCE);

            $user->setProfil($profil)
                ->setEmail($faker->email)
                ->setUsername('formateur'.$i )
                ->setNomComplete($faker->name)
                ->setTelephone($faker->phoneNumber)
                ->setAdresse($faker->email)
                ->setStatus(false)
                ->setGenre($faker->randomElement(["male", "female"]));

            //Génération des User
            $password = $this->encoder->encodePassword($user, 'pass1234');
            $user->setPassword($password);

            $manager->persist($user);
            $brief->setFormateurs($user);
            $manager->persist($brief);
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
