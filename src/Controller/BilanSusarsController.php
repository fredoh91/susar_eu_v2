<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BilanSusarsController extends AbstractController
{
    #[Route('/bilan_susars', name: 'app_bilan_susars')]
    public function index(): Response
    {
        return $this->render('bilan_susars/bilan_susars.html.twig', [

        ]);
    }
}
