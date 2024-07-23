<?php

namespace App\Controller;

use App\Entity\SusarEU;
// use App\Form\DetailSusarEuType;
use App\Entity\SearchSusarEU;
use App\Form\SearchSusarEUType;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// class AfficheSusarEuController extends AbstractController
class SusarEuListeController extends AbstractController
{
    /**
     * Route de test, pour afficher tous les susars
     * cette route, ainsi que la vue associée ("affiche_susar_eu/susar_liste.html.twig"), sera à supprimer par la suite
     *
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
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
    /**
     * Route principale pour l'affichage et la recherche des SUSARs
     *
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/liste_susar_eu', name: 'app_liste_susar_eu')]
    public function liste_susar_eu(ManagerRegistry $doctrine, Request $request, PaginatorInterface $paginator): Response
    {

        $searchSusarEU = new SearchSusarEU;
        $session = $request->getSession();
        $form = $this->createForm(SearchSusarEUType::class, $searchSusarEU);
        $form->handleRequest($request);
        // dd($request);

        $entityManager = $doctrine->getManager();

        
        if ($form->isSubmitted()) {
            $formData = $request->request->all();

            // LOG //
            if (isset($formData)) {
                dump($formData);
            }
            // LOG //

            if (isset($formData['search_susar_eu']['reset'])) {
                dump(1);
                // L'utilisateur a cliqué sur le bouton 'reset'
                $session->remove('search_susar_eu');
                return $this->redirectToRoute('app_liste_susar_eu');
                // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
            } elseif (isset($formData['search_susar_eu']['recherche'])) {
                // L'utilisateur a cliqué sur le bouton 'recherche'
                if ($form->isValid()) {
                    dump(2);
                    // Stocker les critères de recherche dans la session
                    $session->set('search_susar_eu', $searchSusarEU);
                    // Le formulaire est valide, on peut faire la recherche    
                    $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU);
                } else {
                    // Formulaire soumis, mais invalide
                }
            } else {
                
                dump(3);
                // Affichage de tous les susars par defaut :
                // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
            }
        } else {
            // Vérifiez si la requête contient des paramètres de pagination
            $page = $request->query->getInt('page', 1);
            if ($page > 1 && $session->has('search_susar_eu')) {
                
                dump(4);
                // L'utilisateur a cliqué sur un bouton du paginator
                $searchSusarEU = $session->get('search_susar_eu');
                $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU);
            } else {
                // On test si la variable de session 'search_susar_eu' n'est pas vide
                if ($session->has('search_susar_eu')) {
                    dump(5);
                    // On récupère la variable de session 'search_susar_eu'
                    $searchSusarEU = $session->get('search_susar_eu');
                    // peut etre qu'on pourrait également tester sur quelle page on se trouve ? : 
                    //          $page = $request->query->getInt('page', 1); 
                    //          puis  if ($page > 1 && $session->has('search_susar_eu')) {}
                    // dump($searchSusarEU);
                    $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU);
                } else {
                    dump(6);
                    // Affichage de tous les susars par défaut (lors de la première visite)
                    $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();

                }
            }            
            // // Affichage de tous les susars par defaut :
            // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
        }
        $NbSusar = count($TousSusars);
        $Susars = $paginator->paginate(
            $TousSusars, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            50 // Nombre de résultats par page
        );

        return $this->render('affiche_susar_eu/susar_eu_liste.html.twig', [
            'Susars' => $Susars,
            // 'TousSusars' => $TousSusars, // Requête contenant les données à paginer
            'form' => $form->createView(),
            'NbSusar' => $NbSusar,
        ]);
    }

}
