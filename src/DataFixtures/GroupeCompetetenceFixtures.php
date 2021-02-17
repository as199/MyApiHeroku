<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Entity\GroupeCompetence;
use App\Entity\Niveau;
use App\Entity\Promo;
use App\Entity\Referenciel;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class GroupeCompetetenceFixtures extends Fixture
{

        public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $tabDev=['html','php','javascript','mysql','Anglais des affaires','Marketing','Community management'];
        $tabLibelle=['Developpement web','Gestions'];
        $tabDescriptif=['Descritif en Developpement web', 'Descritif en Gestion'];
        $promo = new Promo();
        $promo->setNomPromo("promo 2")
            ->setLieu("Dakar ODC ")
            ->setDescription($faker->text)
            ->setFabrique("Developpeur web")
            ->setLangue("Français")
            ->setDateFinProvisoire(new DateTime("2010-12-16"))
            ->setDateFinReel(new DateTime("2020-12-16"))
            ->setStatus(false)
            ->setAvatar($faker->imageUrl($width = 640, $height = 480))
            ->setFabrique("Developpeur web")
            ->setTitre( "les codeurs");
        $grpcompetences1 = new GroupeCompetence();
        for ($j=0; $j<2; $j++) {

            $grpcompetences1->setLibelle($tabLibelle[$j])
                ->setDescriptif($tabDescriptif[$j]);
            $manager->persist($grpcompetences1);
            for ($i = 0; $i < 7; $i++) {
                $competences = new Competence();
                $competences->setLibelle($tabDev[$i]);
                for($j=0; $j<3; $j++){
                    $niveau= new Niveau();
                    $niveau->setLibelle("Niveau ".($j+1))
                        ->setCritereEvalution("Critere Evaluation ".($j+1))
                        ->setGroupeAction("Groupe Action ".($j+1));
                    $manager->persist($niveau);
                    $competences->addNiveau($niveau);
                }
                $competences ->addGroupeCompetence($grpcompetences1);
                $manager->persist($competences);
            }
        }
        $referentiel= new Referenciel();
        $referentiel
            ->setLibelle('Referentiel DevWeb')
            ->setPresentation('referentiel devWeb')
            ->setProgrammes('algo, programmation web, mobile')
            ->setCriteradmission('avoir moins de 35 ans')
            ->setCritereEvaluation('test logique, base de donnee, math, programmation')
            ->addGroupeCompetence($grpcompetences1)
        ;
        $manager->persist($referentiel);
        $promo->addReferenciel($referentiel);
        $referentiel1= new Referenciel();
        $referentiel1
            ->setLibelle('Referentiel Marketing Digital')
            ->setPresentation('referentiel marketing')
            ->setProgrammes('anglais, marketing , community management')
            ->setCriteradmission('avoir moins de 35 ans')
            ->setCritereEvaluation('test anglais, , francais')
            ->addGroupeCompetence($grpcompetences1)
        ;
        $manager->persist($referentiel1);
        $promo->addReferenciel($referentiel1);
        $manager->persist($promo);
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
