<?php

namespace App\DataFixtures;

use App\Entity\Brief;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BriefFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {$faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i ++) {
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
                ->setEtat($faker->randomElement(['brouillon', 'valide']))
                //->setFormateur($this->getReference(FormateurFixtures::FORM.$i))
               // ->setReferentiel($this->getReference(ReferentielsFixtures::REF.$i))
            ;
            //$brief->addTag($tag);
            $manager->persist($brief);

        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        // TODO: Implement getDependencies() method.
        return [ReferentielsFixtures::class];
    }
}
