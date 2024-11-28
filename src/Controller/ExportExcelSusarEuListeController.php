<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExportExcelSusarEuListeController extends AbstractController
{
    #[Route('/export_excel_susar_eu_liste_old', name: 'app_export_excel_susar_eu_liste_old')]
    public function exportExcel(Request $request): Response
    {
        $searchSusarEU = json_decode($request->query->get('searchSusarEU'),true);
        $triSearchSusarEU = json_decode($request->query->get('triSearchSusarEU'),true);

        dd( $searchSusarEU, $triSearchSusarEU);
        
        return $this->render('export_excel_susar_eu_liste/index.html.twig', [
            'controller_name' => 'ExportExcelSusarEuListeController',
        ]);
    }
}
