<?php

namespace App\Controller;

use App\Entity\IntervenantSubstanceDMM;
use App\Form\IntervenantSubstanceDMMType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\IntervenantsANSMRepository;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\IntervenantSubstanceDMM_detailType;
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

    #[Route('/intervenant_substance/modif/{id}', name: 'app_intervenant_substance_modif')]
    public function liste_intervenant_substance_modif(ManagerRegistry $doctrine, int $id, IntervenantsANSMRepository $intervenantsRepository, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $IntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findIntSubById($id);

        $evaluateur = $IntSub->getEvaluateur();
        $intervenant = $intervenantsRepository->findOneBy(['evaluateur' => $evaluateur]);

        // $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub);
        $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub, [
            'evaluateur_choice' => $evaluateur ? "$evaluateur|{$intervenant->getDmm()}|{$intervenant->getPoleCourt()}" : null,
            'dmm' => $intervenant ? $intervenant->getDmm() : '',
            'pole_court' => $intervenant ? $intervenant->getPoleCourt() : '',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $formData = $request->request->all();

            $newEvalua = explode("|", $formData["intervenant_substance_dmm_substances"]["evaluateur"])[0];

            $newIntervenant = $intervenantsRepository->findOneBy(['evaluateur' => $newEvalua]);

            $IntSub->setEvaluateur($newEvalua);
            $IntSub->setDMM($newIntervenant->getDMM());
            $IntSub->setPoleCourt($newIntervenant->getPoleCourt());
            $IntSub->setPoleLong($newIntervenant->getPoleLong());

            $entityManager->flush();

            return $this->redirectToRoute('app_intervenant_substance');
        
        }

        return $this->render('intervenant_substance/liste_intervenant_substance_modif.html.twig', [
            'IntSub' => $IntSub,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/intervenant_substance/crea', name: 'app_intervenant_substance_crea')]
    public function liste_intervenant_substance_crea(ManagerRegistry $doctrine, IntervenantsANSMRepository $intervenantsRepository, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        // $IntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findIntSubById($id);
        $IntSub = new IntervenantSubstanceDMM();
        
        $evaluateur = $IntSub->getEvaluateur();
        // $intervenant = $intervenantsRepository->findOneBy(['evaluateur' => $evaluateur]);

        $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub);

        // $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub, [
        //     'evaluateur_choice' => $evaluateur ? "$evaluateur|{$intervenant->getDmm()}|{$intervenant->getPoleCourt()}" : null,
        //     'dmm' => $intervenant ? $intervenant->getDmm() : '',
        //     'pole_court' => $intervenant ? $intervenant->getPoleCourt() : '',
        // ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $formData = $request->request->all();

            $newEvalua = explode("|", $formData["intervenant_substance_dmm_substances"]["evaluateur"])[0];

            $newIntervenant = $intervenantsRepository->findOneBy(['evaluateur' => $newEvalua]);

            $IntSub->setEvaluateur($newEvalua);
            $IntSub->setDMM($newIntervenant->getDMM());
            $IntSub->setPoleCourt($newIntervenant->getPoleCourt());
            $IntSub->setPoleLong($newIntervenant->getPoleLong());

            $entityManager->flush();

            return $this->redirectToRoute('app_intervenant_substance');
        
        }

        return $this->render('intervenant_substance/liste_intervenant_substance_crea.html.twig', [
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
