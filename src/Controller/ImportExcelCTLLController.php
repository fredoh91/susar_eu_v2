<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ImportExcelCTLLController extends AbstractController{
    #[Route('/import/excel/c/t/l/l', name: 'app_import_excel_c_t_l_l')]
    public function index(): Response
    {
        return $this->render('import_excel_ctll/index.html.twig', [
            'controller_name' => 'ImportExcelCTLLController',
        ]);
    }
}
