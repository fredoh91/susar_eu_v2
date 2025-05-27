<?php

namespace App\Controller;

use App\Entity\ImportCtllFicExcel;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class BilanImportsCtllController extends AbstractController
{
    #[Route('/bilan_imports_ctll', name: 'app_bilan_imports_ctll')]
    #[IsGranted(new Expression('is_granted("ROLE_DMFR_REF") or is_granted("ROLE_SURV_PILOTEVEC")'))]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        
        $repository = $entityManager->getRepository(ImportCtllFicExcel::class);
        $bilanImportsCtll = $repository->findAllOrderDateImportDesc();
        return $this->render('bilan_imports_ctll/affiche_bilan_import_ctll.html.twig', [
            'bilanImportsCtll' => $bilanImportsCtll,
        ]);
    }
}
