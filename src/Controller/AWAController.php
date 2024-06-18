<?php

namespace App\Controller;

use App\Entity\SusarEU;
use App\Entity\SubstancePt;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AWAController extends AbstractController
{
    #[Route('/awa_lst_SA_PT/{idsusar}', name: 'app_awa_lst_SA_PT')]
    public function lst_SA_PT(int $idsusar, ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $Susar = $entityManager->getRepository(SusarEU::class)->findOneById($idsusar);

        $SubPT = $Susar->getSubstancePts();

        dump($SubPT);
        dd($SubPT->getActiveSubstanceHighLevel());
        return $this->render('awa/liste_couple_substance_EI.html.twig', [
            'idsusar' => $idsusar,
            'Susar' => $Susar,
        ]);
    }
}
