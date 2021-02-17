<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilSortiController extends AbstractController
{
    /**
     * @Route("/profil/sorti", name="profil_sorti")
     */
    public function index(): Response
    {
        return $this->render('profil_sorti/index.html.twig', [
            'controller_name' => 'ProfilSortiController',
        ]);
    }
}
