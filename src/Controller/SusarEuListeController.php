<?php

namespace App\Controller;

use App\Entity\SusarEU;
// use App\Form\DetailSusarEuType;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// class AfficheSusarEuController extends AbstractController
class SusarEuListeController extends AbstractController
{
    #[Route('/affiche_susar_eu', name: 'app_affiche_susar_eu')]
    public function affiche_susar_eu(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {

        $entityManager = $doctrine->getManager();
        $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
        $NbSusar = count($TousSusars);
        // dump(count($TousSusars));
        $Susars = $paginator->paginate(
            $TousSusars, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            50 // Nombre de résultats par page
        );


        return $this->render('affiche_susar_eu/susar_liste.html.twig', [
            'Susars' => $Susars,
            // 'TousSusars' => $TousSusars, // Requête contenant les données à paginer
            'NbSusar' => $NbSusar,
        ]);
    }
    #[Route('/liste_susar_eu', name: 'app_liste_susar_eu')]
    public function liste_susar_eu(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {

        $entityManager = $doctrine->getManager();
        $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
        $NbSusar = count($TousSusars);
        // dump(count($TousSusars));
        $Susars = $paginator->paginate(
            $TousSusars, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            50 // Nombre de résultats par page
        );


        return $this->render('affiche_susar_eu/susar_eu_liste.html.twig', [
            'Susars' => $Susars,
            // 'TousSusars' => $TousSusars, // Requête contenant les données à paginer
            'NbSusar' => $NbSusar,
        ]);
    }

}
