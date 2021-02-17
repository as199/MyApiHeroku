<?php


namespace App\DataFixtures;

use App\Entity\ProfilSorti;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilSortiFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $profilSortie = ["developpeur front", "developpeur back", "fullstack", "CMS", "integrateur", "designer", "CM", "Data"];
        $times = 10;

        for ($i = 0; $i < $times; $i++) {
            $profilAprrenant = new ProfilSorti();
            $randomProfilSortie = random_int(0,count($profilSortie)-1);
            $profilAprrenant->setLibelle($profilSortie[$randomProfilSortie]);
            $manager->persist($profilAprrenant);
        }
        $manager->flush();
    }
}