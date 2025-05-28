<?php

namespace App\Controller;

use App\Entity\SusarEU;
// use App\Form\DetailSusarEuType;
use Psr\Log\LoggerInterface;
use App\Entity\SearchSusarEU;
use App\Form\SearchSusarEUType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\VarDumper\VarDumper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Constraints\Length;
use App\Service\SusarEUQueryService;

// class AfficheSusarEuController extends AbstractController

#[IsGranted(new Expression('is_granted("ROLE_DMM_EVAL") or is_granted("ROLE_SURV_PILOTEVEC")'))]
class SusarEuListeController extends AbstractController
{
    private $logger;
    private $kernel;
    private $susarEUQueryService;

    public function __construct(LoggerInterface $logger, KernelInterface $kernel, SusarEUQueryService $susarEUQueryService)
    {
        $this->logger = $logger;
        $this->kernel = $kernel;
        $this->susarEUQueryService = $susarEUQueryService;
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/liste_susar_eu_init', name: 'app_liste_susar_eu_init')]
    public function liste_susar_eu_init(Request $request): Response
    {
        $session = $request->getSession();

        if ($session->has('search_susar_eu')) {
            $session->remove('search_susar_eu');
        }
        if ($session->has('tri_search_susar_eu')) {
            $session->remove('tri_search_susar_eu');
        }

        return $this->redirectToRoute('app_liste_susar_eu');
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

        // $searchSusarEU = $session->get('search_susar_eu', $defaultSearchSusarEU);

        $defaultTriSearchSusarEU = [
            // ['field' => 'statusdate', 'direction' => 'DESC'],
            ['field' => 'createdAt', 'direction' => 'DESC'],
            ['field' => 'sponsorstudynumb', 'direction' => 'ASC'],
            ['field' => 'worldWide_id', 'direction' => 'ASC']
        ];

        if (!$session->has('search_susar_eu')) {
            // Il n'y a pas de variable 'search_susar_eu' dans la session
            // - On l'initialise avec les valeurs par défaut
            $session->set('search_susar_eu', $defaultSearchSusarEU);
            // - On initialise la variable 'tri_search_susar_eu' avec les valeurs par défaut
            $searchSusarEU = $defaultSearchSusarEU;
        } else {
            $searchSusarEU = $session->get('search_susar_eu');
        }

        if (!$session->has('tri_search_susar_eu')) {
            $session->set('tri_search_susar_eu', $defaultTriSearchSusarEU);
            $triSearchSusarEU = $defaultTriSearchSusarEU;
        } else {
            $triSearchSusarEU = $session->get('tri_search_susar_eu');
        }

        if ($this->kernel->getEnvironment() === 'dev') {
            // dump($session);
            if ($session->has('search_susar_eu')) {
                $this->logger->info('Session search_susar_eu:', ['value' => $session->get('search_susar_eu') ?? 'null']);
            } else {
                $this->logger->info('Session search_susar_eu: vide');
            }
            if ($session->has('tri_search_susar_eu')) {
                $this->logger->info('Session tri_search_susar_eu:', ['value' => $session->get('tri_search_susar_eu') ?? 'null']);
            } else {
                $this->logger->info('tri_search_susar_eu : vide');
            }
        }

        $form = $this->createForm(SearchSusarEUType::class, $searchSusarEU, [
            'show_import_dates' => $this->isGranted('ROLE_SUPER_ADMIN') || $this->isGranted('ROLE_SURV_PILOTEVEC'),
        ]);

        $form->handleRequest($request);

        $entityManager = $doctrine->getManager();

        // Le formulaire a été soumis (POST)
        if ($form->isSubmitted()) {

            if ($form->get('recherche')->isClicked()) {

                if ($this->kernel->getEnvironment() === 'dev') {
                    dump('liste_susar_eu - cas 2 - Recherche');
                    $this->logger->info('liste_susar_eu - cas 2 - Recherche');
                }

                if ($form->isValid()) {

                    if ($this->kernel->getEnvironment() === 'dev') {
                        dump('liste_susar_eu - cas 2 - Recherche - Formulaire valide');
                        $this->logger->info('liste_susar_eu - cas 2 - Recherche - Formulaire valide');
                    }
                    $page = 1;
                    // Stocker les critères de recherche dans la session

                    $searchSusarEU = $form->getData();

                    $session->set('search_susar_eu', $searchSusarEU);
                    $session->set('tri_search_susar_eu', $defaultTriSearchSusarEU);

                    if ($this->kernel->getEnvironment() === 'dev') {
                        dump($form->getData());
                        $this->logger->info($form->getData());
                        $this->logger->info($searchSusarEU);
                    }

                    // Le formulaire est valide, on peut faire la recherche
                    // $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU,$triSearchSusarEU);
                    $TousSusars = $this->susarEUQueryService->findBySearchSusarEuListe($searchSusarEU, $triSearchSusarEU);
                    // Redirection vers la route sans paramètres de page
                    return $this->redirectToRoute('app_liste_susar_eu');
                } else {
                    if ($this->kernel->getEnvironment() === 'dev') {
                        dump('liste_susar_eu - cas 2 - Recherche - Formulaire INvalide');
                        $this->logger->info('liste_susar_eu - cas 2 - Recherche - Formulaire INvalide');
                    }
                    // Formulaire soumis, mais invalide
                }
            }

            if ($form->get('exportExcel')->isClicked()) {
                if ($this->kernel->getEnvironment() === 'dev') {
                    dump('liste_susar_eu - cas 7 - Export Excel');
                    $this->logger->info('liste_susar_eu - cas 7 - Export Excel');
                }



                // if (!$session->has('search_susar_eu')) {
                //     // Il n'y a pas de variable 'search_susar_eu' dans la session
                //     // - On l'initialise avec les valeurs par défaut
                //     $session->set('search_susar_eu', $defaultSearchSusarEU);
                //     // - On initialise la variable 'tri_search_susar_eu' avec les valeurs par défaut
                //     $searchSusarEU = $defaultSearchSusarEU;
                // } else {
                //     $searchSusarEU = $session->get('search_susar_eu');
                // }

                // if (!$session->has('tri_search_susar_eu')) {
                //     $session->set('tri_search_susar_eu', $defaultTriSearchSusarEU);
                //     $triSearchSusarEU = $defaultTriSearchSusarEU;
                // } else {
                //     $triSearchSusarEU = $session->get('tri_search_susar_eu');
                // }


                return $this->redirectToRoute('app_export_excel_susar_eu_liste');
            }

            if ($form->get('reset')->isClicked()) {
                if ($this->kernel->getEnvironment() === 'dev') {
                    dump('liste_susar_eu - cas 1 - Reset');
                    $this->logger->info('liste_susar_eu - cas 1 - Reset');
                }

                $session->set('search_susar_eu', $defaultSearchSusarEU);
                $session->set('tri_search_susar_eu', $defaultTriSearchSusarEU);
                return $this->redirectToRoute('app_liste_susar_eu');
            }
        } else {
            // Première arrivée sur le formulaire (GET)

            $form = $this->createForm(SearchSusarEUType::class, $searchSusarEU, [
                'show_import_dates' => $this->isGranted('ROLE_SUPER_ADMIN') || $this->isGranted('ROLE_SURV_PILOTEVEC'),
            ]);

            // $TousSusars = $entityManager->getRepository(SusarEU::class)->findBySearchSusarEuListe($searchSusarEU,$triSearchSusarEU);
            $TousSusars = $this->susarEUQueryService->findBySearchSusarEuListe($searchSusarEU, $triSearchSusarEU);
        }

        $NbSusar = count($TousSusars);
        $Susars = $paginator->paginate(
            $TousSusars, // Requête contenant les données à paginer
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            $nbResuPage // Nombre de résultats par page
        );


        if ($this->kernel->getEnvironment() === 'dev') {
            $this->logger->info('nb susar : ' . $NbSusar);
        }


        return $this->render('affiche_susar_eu/susar_eu_liste.html.twig', [
            'Susars' => $Susars,
            // 'TousSusars' => $TousSusars, // Requête contenant les données à paginer
            'form' => $form->createView(),
            'NbSusar' => $NbSusar,
        ]);
    }


    public function export_Excel($TousSusars)
    {
        dd($TousSusars);
    }
}
