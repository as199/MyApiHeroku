<?php

namespace App\DataFixtures;

use App\Entity\Promo;
use App\Entity\Referenciel;
use App\Repository\GroupeCompetenceRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class ReferencielFixtures extends Fixture
{

    private $groupeCompetencesRepository;
    const REF = 'ref';

    public function __construct(GroupeCompetenceRepository $groupeCompetencesRepository)
    {
        $this->groupeCompetencesRepository = $groupeCompetencesRepository;

    }

    public function load(ObjectManager $manager)
    {


        $referentiels = ['Développement Web et mobile', 'reference digitale', 'data science'];
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $ref = new Referenciel();
            $grpcom = $this->groupeCompetencesRepository->find(1);
            $randomRef= random_int(0, count($referentiels) - 1);
            $ref->setLibelle($referentiels[$randomRef]);
            $ref->setPresentation($faker->text);
            $ref->setCritereEvaluation("Un portfolio comprenant la réalisation d'au moins 9 projets tout au long de la formation");
            $ref->setCriteradmission('Une soutenance devant un jury de professionnels');
            $ref->setProgrammes('uploader le programme');

            $this->addReference(ReferencielFixtures::REF.$i, $ref);
            $manager->persist($ref);
        }

        $manager->flush();
    }
}
