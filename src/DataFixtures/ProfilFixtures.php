<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilFixtures extends Fixture
{
    public const ADMIN_REFERENCE = "ADMIN";
    public const FORMATEUR_REFERENCE  = "FORMATEUR";
    public const APPRENANT_REFERENCE  = "APPRENANT";
    public const CM_REFERENCE  = "CM";

    public function load(ObjectManager $manager)
    {
        $profils =["ADMIN","FORMATEUR","APPRENANT","CM"];
        foreach ($profils as $key => $libelle) {
            $profil = new Profil();
            $profil->setLibelle($libelle);
            $profil->setStatus(false);
            $manager->persist($profil);
            if($libelle == "ADMIN"){
                $this->addReference(self::ADMIN_REFERENCE,$profil);
            }elseif ($libelle == "FORMATEUR"){
                $this->addReference(self::FORMATEUR_REFERENCE,$profil);
            }elseif ($libelle == "APPRENANT"){
                $this->addReference(self::APPRENANT_REFERENCE,$profil);
            }elseif ($libelle == "CM"){
                $this->addReference(self::CM_REFERENCE,$profil);
            }
            $manager->flush();



        }
}
}
