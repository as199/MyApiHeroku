<?php

namespace App\DataFixtures;

use App\Entity\Competence;
use App\Entity\CompetencesValides;
use App\Entity\Groupe;
use App\Entity\GroupeCompetence;
use App\Entity\GroupeTag;
use App\Entity\GroupeTags;
use App\Entity\Niveau;
use App\Entity\ProfilSorti;
use App\Entity\Promo;
use App\Entity\Referenciel;
use App\Entity\Tag;
use App\Entity\Tags;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Admin;
use App\Entity\Profil;
use App\Entity\Formateur;
use App\Entity\Cm;
use App\Entity\Apprenant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {/*
        $faker = Factory::create('fr_FR');
        $profils = ["ADMIN", "FORMATEUR", "APPRENANT", "CM"];
        foreach ($profils as $key => $libelle) {
            $profil = new Profil();
            $profil->setLibelle($libelle);
            $manager->persist($profil);
            $manager->flush();
            if ($libelle == "ADMIN") {
                for ($i = 1; $i <= 3; $i++) {
                    $admin = new Admin();
                    $admin->setProfil($profil)
                        ->setTelephone($faker->phoneNumber)
                        ->setUsername($faker->userName)
                        ->setEmail(strtolower($libelle) . $i . '@gmail.com')
                        ->setNomComplete($faker->firstName )
                        ->setAdresse($faker->address)
                        ->setStatus(false)
                        ->setGenre($faker->randomElement(["male", "female"]))
                        ->setAvartar($faker->imageUrl($width = 640, $height = 480));
                    //Génération des User
                    $password = $this->encoder->encodePassword($admin, 'pass1234');
                    $admin->setPassword($password);

                    $manager->persist($admin);
                }
            } elseif ($libelle == "APPRENANT") {
                for ($i = 1; $i <= 4; $i++) {
                    $apprenant = new Apprenant();
                    if($i==3 || $i==4){
                        $apprenant->setProfil($profil)
                            ->setTelephone($faker->phoneNumber)
                            ->setUsername($faker->userName)
                            ->setEmail(strtolower($libelle) . $i . '@gmail.com')
                            ->setNomComplete($faker->firstName .''.$faker->lastName)
                            ->setAdresse($faker->address)
                            ->setStatus(false)
                            ->setGenre($faker->randomElement(["male", "female"]))
                            ->setAvartar($faker->imageUrl($width = 640, $height = 480));
                        //Génération des Users
                        $password = $this->encoder->encodePassword($apprenant, 'pass1234');
                        $apprenant->setPassword($password);

                    }else{
                        $apprenant->setProfil($profil)
                            ->setTelephone($faker->phoneNumber)
                            ->setUsername($faker->userName)
                            ->setEmail(strtolower($libelle) . $i . '@gmail.com')
                            ->setAdresse($faker->address)
                            ->setStatus(false)
                            ->setNomComplete($faker->firstName .''.$faker->lastName)
                            ->setGenre($faker->randomElement(["male", "female"]))
                            ->setAvartar($faker->imageUrl($width = 640, $height = 480));
                        //Génération des Users
                        $password = $this->encoder->encodePassword($apprenant, 'pass1234');
                        $apprenant->setPassword($password);


                    };
                    $manager->persist($apprenant);

                }
            } elseif ($libelle == "CM") {
                for ($i = 1; $i <= 3; $i++) {
                    $cm = new Cm();
                    $cm->setProfil($profil)
                        ->setTelephone($faker->phoneNumber)
                        ->setUsername($faker->userName)
                        ->setEmail(strtolower($libelle) . $i . '@gmail.com')
                        ->setNomComplete($faker->firstName .''.$faker->lastName)
                        ->setAdresse($faker->address)
                        ->setStatus(false)
                        ->setGenre($faker->randomElement(["male", "female"]))
                        ->setAvartar($faker->imageUrl($width = 640, $height = 480));
                    //Génération des Users
                    $password = $this->encoder->encodePassword($cm, 'pass1234');
                    $cm->setPassword($password);

                    $manager->persist($cm);
                }
            } else {
                for ($i = 1; $i <= 3; $i++) {
                    $user = new Formateur();
                    $user->setProfil($profil)
                        ->setTelephone($faker->phoneNumber)
                        ->setUsername($faker->userName)
                        ->setEmail(strtolower($libelle) . $i . '@gmail.com')
                        ->setNomComplete($faker->firstName .''.$faker->lastName)
                        ->setAdresse($faker->address)
                        ->setStatus(false)
                        ->setGenre($faker->randomElement(["male", "female"]))
                        ->setAvartar($faker->imageUrl($width = 640, $height = 480));
                    //Génération des Users
                    $password = $this->encoder->encodePassword($user, 'pass1234');
                    $user->setPassword($password);

                    $manager->persist($user);
                }
            }
        }
        $competence = ["Competence1", "Competence2", "Competence3"];
        foreach ($competence as $key => $libell) {
            $comp = new Competence();
            $comp->setLibelle($libell)
            ->setDescriptif($faker->text)
            ->setStatus(false);

            $manager->persist($comp);
        }
        $competences = ["groupecompetence1", "groupecompetence2", "groupecompetence3"];
        foreach ($competences as $key => $libelle) {
            $comp = new GroupeCompetence();
            $comp->setLibelle($libelle)
                ->setDescriptif($faker->text)
                ->setStatus(false);
            $manager->persist($comp);
        }
        $promos = ["promo1", "promo2"];
        foreach ($promos as $key => $libelle) {
            if ($libelle == "promo1") {
                $promo = new Promo();
                $promo->setNomPromo($libelle)
                    ->setDateFinProvisoire(new \DateTime("2010-12-16"))
                    ->setLangue("francais")
                    ->setTitre("Promo".($key+1))
                    ->setFabrique("ODC")
                    ->setStatus(false)
                    ->setAvatar($faker->imageUrl($width = 640, $height = 480))
                    ->setDescription($faker->text)
                    ->setLieu($faker->address)
                    ->setDateFinReel(new \DateTime("2014-12-16"));
                $manager->persist($promo);
                $manager->flush();

                $groupes =["groupe1","groupe2","groupe3"];
                foreach ($groupes as $key => $libelle){
                    $groupe = new Groupe();
                    if($libelle=="groupe1"){
                        $groupe->setNomGroupe($libelle)
                            ->setPromos($promo)
                            ->setType("principal")
                            ->setDateCreation(new \DateTime("now"))
                            ->setStatus(false);
                    }else{
                        $groupe->setNomGroupe($libelle)
                            ->setPromos($promo);
                    };
                    $manager->persist($groupe);

                }
            }elseif ($libelle == "promo2") {
                $promo = new Promo();
                $promo->setNomPromo($libelle)
                    ->setDateFinProvisoire(new \DateTime("2010-12-16"))
                    ->setDateFinReel(new \DateTime("2014-12-16"))
                    ->addApprenant($apprenant)
                    ->addGroupe($groupe);
                $manager->persist($promo);
                $manager->flush();

                $groupes =["groupe1","groupe2","groupe3","groupe4","groupe5"];
                foreach ($groupes as $key => $libelle){
                    $groupe = new Groupe();
                        $groupe->setNomGroupe($libelle)
                            ->setType("secondaire")
                            ->setDateCreation(new \DateTime("now"))
                            ->setStatus(false);

                    $manager->persist($groupe);

                }
            }
        }




        $tagroup = ["groupeTag1","groupeTag2","groupeTag3"];
        foreach ($tagroup as $key => $libelle) {
            $tagr = new  GroupeTags();
            $tagr->setLibelle($libelle)
            ->setStatus($libelle);
                $tags =["tag1","tag2","tag3","tag4"];
        foreach ($tags as $key => $libelle) {
            $tag = new  Tags();
            $tag->setLibelle($libelle)
                ->setDescriptif($faker->text)
                ->setStatus(false);
            $manager->persist($tag);
        }
            $tagr ->addTag($tag);
            $manager->persist($tagr);
        }
        $competences = ["php","java","mysql"];
        foreach ($competences as $item){
            $competence = new Competence();
            $competence->setLibelle($item)
                        ->setDescriptif($faker->text)
                        ->setStatus(false);
            $niveaux = ["niveau1","niveau2","niveau3"];
            foreach ($niveaux as $key => $libelle) {
                $niveau = new  Niveau();
                $niveau->setLibelle($libelle);
                $manager->persist($niveau);
                $competence->addNiveau($niveau);
            }
            $competencevalides = ["competenceValide1","competenceValide2","competenceValide3"];
            foreach ($competencevalides as $key => $libelle) {
                $competencevalide = new  CompetencesValides();
                $competencevalide->setNiveau1( false)
                                    ->setNiveau2(true)
                                    ->setNiveau3(false)
                                    ->setApprenants($apprenant);
                $manager->persist($niveau);
                $competence->addNiveau($niveau);
            }
        }

        $referenciel = ["referenciel1","referenciel2","referenciel3"];
        foreach ($referenciel as $key => $libelle) {
            $referenciel = new  Referenciel();
            $referenciel->setLibelle($libelle)
                        ->setCritereEvaluation($faker->text)
                        ->setPresentation($faker->text)
                        ->setCriteradmission($faker->text)
                        ->setProgramme($faker->text)
                        ->setStatus(false)
                        ->addCompetencesValide($competencevalide)
                        ->addPromo($promo);

            $manager->persist($referenciel);
        }
        $profilso = ["profilSorti1","profilSorti2","profilSorti3"];
        foreach ($profilso as $key => $libelle) {
            $profilsorti = new  ProfilSorti();
            $profilsorti->setLibelle($libelle)
            ->setLibelle($faker->text)
            ->setStatus(false);


            $manager->persist($profilsorti);
        }
        $manager->flush();*/
    }
}