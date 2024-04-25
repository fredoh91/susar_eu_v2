<?php

namespace App\Controller;

use App\Entity\IntervenantSubstanceDMM;
use App\Form\IntervenantSubstanceDMMType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IntervenantSubstanceController extends AbstractController
{
    #[Route('/intervenant_substance', name: 'app_intervenant_substance')]
    public function liste_intervenant_substance(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $TousIntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findAllSortHL_SA();

        $NbIntSub = count($TousIntSub);

        return $this->render('intervenant_substance/liste_intervenant_substance.html.twig', [
            'TousIntSub' => $TousIntSub,
            'NbIntSub' => $NbIntSub,
        ]);
    }

    #[Route('/intervenant_substance/detail/{id}', name: 'app_intervenant_substance_detail')]
    public function liste_intervenant_substance_detail(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $IntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findIntSubById($id);
        $form = $this->createForm(IntervenantSubstanceDMMType::class, $IntSub);
        
        return $this->render('intervenant_substance/liste_intervenant_substance_detail.html.twig', [
            'IntSub' => $IntSub,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'afficher la liste des high level substance name pour faire un copier-coller sur la plateforme EVDAS et ainsi récupérer le fichier "active substance grouping"
     *
     * @param ManagerRegistry $doctrine
     * @return Response
     */
    #[Route('/liste_HL_SA', name: 'app_liste_HL_SA')]
    public function liste_HL_SA(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $TousHL_SA = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findHL_SA_Rgp();

        $NbHL_SA = count($TousHL_SA);

        return $this->render('intervenant_substance/liste_HL_SA.html.twig', [
            'TousHL_SA' => $TousHL_SA,
            'NbHL_SA' => $NbHL_SA,
        ]);
    }
}
