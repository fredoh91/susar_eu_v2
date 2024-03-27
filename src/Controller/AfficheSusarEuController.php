<?php

namespace App\Controller;

use App\Entity\SusarEU;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AfficheSusarEuController extends AbstractController
{
    #[Route('/affiche_susar_eu', name: 'app_affiche_susar_eu')]
    public function index(ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();
        $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();

        return $this->render('affiche_susar_eu/liste_susar.html.twig', [
            'TousSusars' => $TousSusars, // Requête contenant les données à paginer
            'controller_name' => 'AfficheSusarEuController',
        ]);
    }
}
