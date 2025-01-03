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
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// class AfficheSusarEuController extends AbstractController

#[IsGranted(new Expression('is_granted("ROLE_DMM_EVAL") or is_granted("ROLE_SURV_PILOTEVEC")'))]
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

        $nbResuPage = 50;
        $session = $request->getSession();

        $defaultSearchSusarEU = new SearchSusarEU();
        // Set default values for SearchSusarEU properties
        $defaultSearchSusarEU->setNiveau1(true);
        $defaultSearchSusarEU->setNiveau2a(true);
        $defaultSearchSusarEU->setNiveau2b(true);
        $defaultSearchSusarEU->setNiveau2c(true);
        $defaultSearchSusarEU->setCasArchive("non_archive");

        $searchSusarEU = $session->get('search_susar_eu', $defaultSearchSusarEU);



        // $searchSusarEU = new SearchSusarEU;
        
        // $triSearchSusarEU = [
        //             ['field' => 'statusdate', 'direction' => 'DESC'],
        //             ['field' => 'sponsorstudynumb', 'direction' => 'ASC'],
        //             ['field' => 'worldWide_id', 'direction' => 'ASC']
        // ];
        $defaultTriSearchSusarEU = [
            ['field' => 'statusdate', 'direction' => 'DESC'],
            ['field' => 'sponsorstudynumb', 'direction' => 'ASC'],
            ['field' => 'worldWide_id', 'direction' => 'ASC']
        ];


        // Initialize session variables with default values if they are not set
        if (!$session->has('search_susar_eu')) {
            $session->set('search_susar_eu', $defaultSearchSusarEU);
        }
        if (!$session->has('tri_search_susar_eu')) {
            $session->set('tri_search_susar_eu', $defaultTriSearchSusarEU);
        }



        $form = $this->createForm(SearchSusarEUType::class, $searchSusarEU);
        
        $form->handleRequest($request);
        // dd($request);

        $entityManager = $doctrine->getManager();


        if ($form->isSubmitted()) {
            $formData = $request->request->all();

            // // LOG //
            // if (isset($formData)) {
            //     dump($formData);
            // }
            // // LOG //

            if (isset($formData['search_susar_eu']['reset'])) {
                dump('liste_susar_eu - cas 1');
                // L'utilisateur a cliqué sur le bouton 'reset'
                // $session->remove('search_susar_eu');
                // $session->remove('tri_search_susar_eu');
                $session->set('search_susar_eu', $defaultSearchSusarEU);
                $session->set('tri_search_susar_eu', $defaultTriSearchSusarEU);
                return $this->redirectToRoute('app_liste_susar_eu');
                // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
            } elseif (isset($formData['search_susar_eu']['recherche'])) {
                // L'utilisateur a cliqué sur le bouton 'recherche' 
                // On reset le parametre ?page= dans l'url
                if ($form->isValid()) {
                    dump('liste_susar_eu - cas 2');
                    // $page = null;
                    $page = 1;
                    // Stocker les critères de recherche dans la session
                    $session->set('search_susar_eu', $searchSusarEU);
                    $session->set('tri_search_susar_eu', $defaultTriSearchSusarEU);
                    // Le formulaire est valide, on peut faire la recherche
                    // dump('$page : ' . $page);
                    // dump('$nbResuPage : ' . $nbResuPage);
                    // dump('$searchSusarEU : ' . $searchSusarEU);
                    // $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU,$triSearchSusarEU, $page, $nbResuPage);
                    $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU,$defaultTriSearchSusarEU);
                } else {
                    // Formulaire soumis, mais invalide
                }

                
            } elseif (isset($formData['search_susar_eu']['exportExcel'])) {
                dump('liste_susar_eu - cas 7');
                // L'utilisateur a cliqué sur le bouton "Export Excel""
                
                // $searchSusarEU = $session->get('search_susar_eu');
                // $triSearchSusarEU = $session->get('tri_search_susar_eu');

                // return $this->redirectToRoute('app_export_excel_susar_eu_liste', [
                //     'searchSusarEU' => json_encode($searchSusarEU),
                //     'triSearchSusarEU' => json_encode($triSearchSusarEU)
                // ]);

                return $this->redirectToRoute('app_export_excel_susar_eu_liste');
            } else {
                
                dump(3);
                // Affichage de tous les susars par defaut :
                // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
            }
        } else {
            // Vérifiez si la requête contient des paramètres de pagination
            $page = $request->query->getInt('page', 1);

            if ($page > 1 && $session->has('search_susar_eu')) {
                
                dump('liste_susar_eu - cas 4');
                // L'utilisateur a cliqué sur un bouton du paginator
                $searchSusarEU = $session->get('search_susar_eu');
                $triSearchSusarEU = $session->get('tri_search_susar_eu');

                // $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU,$triSearchSusarEU, $page, $nbResuPage);
                $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU,$triSearchSusarEU);
            } else {
                // On test si la variable de session 'search_susar_eu' n'est pas vide
                if ($session->has('search_susar_eu')) {
                    // l'utilisateur arrive sur cette page pour la premiere fois
                    dump('liste_susar_eu - cas 5');
                    // On récupère la variable de session 'search_susar_eu'
                    // $searchSusarEU = $session->get('search_susar_eu');
                    // $session->remove('search_susar_eu');
                    // $session->remove('tri_search_susar_eu');
                    $session->set('search_susar_eu', $defaultSearchSusarEU);
                    $session->set('tri_search_susar_eu', $defaultTriSearchSusarEU);   
                    
                    $form = $this->createForm(SearchSusarEUType::class, $defaultSearchSusarEU);

                    // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
                    // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAllOrder('creationdate', 'DESC');
                    $TousSusars = $entityManager->getRepository(SusarEU::class)->findAllOrder($defaultTriSearchSusarEU);

                    // peut etre qu'on pourrait également tester sur quelle page on se trouve ? : 
                    //          $page = $request->query->getInt('page', 1); 
                    //          puis  if ($page > 1 && $session->has('search_susar_eu')) {}
                    // dump($searchSusarEU);
                    // $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU,$triSearchSusarEU, $page, $nbResuPage);
                    // $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU,$triSearchSusarEU);
                } else {
                    dump('liste_susar_eu - cas 6');
                    // Affichage de tous les susars par défaut (lors de la première visite)
                    // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
                    // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAllOrder('creationdate', 'DESC');
                    $TousSusars = $entityManager->getRepository(SusarEU::class)->findAllOrder($defaultTriSearchSusarEU);

                }
            }            
            // // Affichage de tous les susars par defaut :
            // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
        }
        $NbSusar = count($TousSusars);
        $Susars = $paginator->paginate(
            $TousSusars, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $nbResuPage // Nombre de résultats par page
        );

        return $this->render('affiche_susar_eu/susar_eu_liste.html.twig', [
            'Susars' => $Susars,
            // 'TousSusars' => $TousSusars, // Requête contenant les données à paginer
            'form' => $form->createView(),
            'NbSusar' => $NbSusar,
        ]);
    }


    public function export_Excel($TousSusars) {
        dd($TousSusars);
    }
}
