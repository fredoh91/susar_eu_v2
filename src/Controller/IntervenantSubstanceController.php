<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\IntervenantSubstanceDMM;
use App\Form\IntervenantSubstanceDMMType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\IntervenantsANSMRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
// use App\Entity\IntervenantSubstanceDMMSubstance;
use App\Form\IntervenantSubstanceDMM_detailType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IntervenantSubstanceController extends AbstractController
{
    #[Route('/intervenant_substance', name: 'app_intervenant_substance')]
    public function liste_intervenant_substance(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $repos = $entityManager->getRepository(IntervenantSubstanceDMM::class);
        $TousIntSub = $repos->findAllSortHL_SA();
        $nbActif = $repos->nbIntSub(false);
        $nbInactif = $repos->nbIntSub(true);

        $NbIntSub = count($TousIntSub);

        return $this->render('intervenant_substance/liste_intervenant_substance.html.twig', [
            'TousIntSub' => $TousIntSub,
            'NbIntSub' => $NbIntSub,
            'NbActif' => $nbActif,
            'NbInactif' => $nbInactif,
        ]);
    }

    #[Route('/intervenant_substance/detail/{id}', name: 'app_intervenant_substance_detail')]
    public function liste_intervenant_substance_detail(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $IntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findIntSubById($id);
        // $form = $this->createForm(IntervenantSubstanceDMMType::class, $IntSub);
        
        return $this->render('intervenant_substance/liste_intervenant_substance_detail.html.twig', [
            'IntSub' => $IntSub,
            // 'form' => $form->createView(),
        ]);
    }

    #[Route('/intervenant_substance/modif/{id}', name: 'app_intervenant_substance_modif')]
    public function liste_intervenant_substance_modif(ManagerRegistry $doctrine, int $id, IntervenantsANSMRepository $intervenantsRepository, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $IntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findIntSubById($id);

        $evaluateur = $IntSub->getEvaluateur();
        $intervenant = $intervenantsRepository->findOneBy(['evaluateur' => $evaluateur]);
        $idIntSub= $IntSub->getId();
        if ($intervenant === null) {
            $this->addFlash(
                        'error', 
                        "Il y a un problème sur le nom de l'évaluateur ({$evaluateur}), contacter l'administrateur de BDD"
                    );
            return $this->redirectToRoute('app_intervenant_substance');
        }

        // $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub);

        // dump('Evaluateur : ',$evaluateur);
        // dump('Intervenant : ', $intervenant);
        // dump($intervenant->getPoleCourt(), 'Pole Court');



        $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub, [
            'evaluateur_choice' => $evaluateur ? "$evaluateur|{$intervenant->getDmm()}|{$intervenant->getPoleCourt()}" : null,
            'dmm' => $intervenant ? $intervenant->getDmm() : '',
            'pole_court' => $intervenant ? $intervenant->getPoleCourt() : '',
            // 'idIntSub' => $idIntSub,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $dateNow = new DateTimeImmutable();

            // On récupère les données des substances liées si elles existent depuis le formulaire
            $substancesData = $formData['intervenant_substance_dmm_substances']['intervenantSubstanceDMMSubstances'] ?? [];

            $formData = $request->request->all();

            $HL_SA = $formData['intervenant_substance_dmm_substances']['ActiveSubstanceHighLevel'];

            $isExist = $entityManager->getRepository(IntervenantSubstanceDMM::class)->isSubstanceExistActiv($HL_SA,$idIntSub);
            $formData = $request->request->all();

            if (!isset($formData['intervenant_substance_dmm_substances']['inactif'])) {
                $inactif = false;
            } else {
                $inactif = $formData['intervenant_substance_dmm_substances']['inactif'];
            }

            if($isExist && $inactif === false){
                // cette substance existe déjà

                $this->addFlash('error', 'La substance suivante existe déjà avec un statut "actif" : ' . $HL_SA);

                return $this->render('intervenant_substance/liste_intervenant_substance_creation.html.twig', [
                    'IntSub' => $IntSub,
                    'form' => $form->createView(),
                ]);
            }

            $newEvalua = explode("|", $formData["intervenant_substance_dmm_substances"]["evaluateur"])[0];

            $newIntervenant = $intervenantsRepository->findOneBy(['evaluateur' => $newEvalua]);

            $IntSub->setEvaluateur($newEvalua);
            $IntSub->setDMM($newIntervenant->getDMM());
            $IntSub->setPoleCourt($newIntervenant->getPoleCourt());
            $IntSub->setPoleLong($newIntervenant->getPoleLong());
            $IntSub->setUpdatedAt($dateNow);

            // On traite manuellement les entités IntervenantSubstanceDMMSubstance liées
            foreach ($IntSub->getIntervenantSubstanceDMMSubstances() as $index => $substance) {
                // Mettre à jour le champ updatedAt
                $substance->setUpdatedAt($dateNow);
            
                // Mettre à jour ActiveSubstanceLowLevel et active_substance_high_level
                if (isset($substancesData[$index])) {
                    $substanceData = $substancesData[$index];
                    $substance->setActiveSubstanceLowLevel($substanceData['ActiveSubstanceLowLevel'] ?? null);
                    $substance->setActiveSubstanceHighLevel($substanceData['active_substance_high_level'] ?? null);
                }
            
                if (!$entityManager->contains($substance)) {
                    // L'entité est nouvelle, initialisons createdAt
                    $substance->setCreatedAt($dateNow);
                    $entityManager->persist($substance);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_intervenant_substance');
        
        }

        return $this->render('intervenant_substance/liste_intervenant_substance_modif.html.twig', [
            'IntSub' => $IntSub,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/intervenant_substance/creation', name: 'app_intervenant_substance_creation')]
    public function liste_intervenant_substance_crea(ManagerRegistry $doctrine, IntervenantsANSMRepository $intervenantsRepository, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        // $IntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findIntSubById($id);
        $IntSub = new IntervenantSubstanceDMM();
        
        $evaluateur = $IntSub->getEvaluateur();
        // $intervenant = $intervenantsRepository->findOneBy(['evaluateur' => $evaluateur]);

        $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $formData = $request->request->all();

            $HL_SA = $formData['intervenant_substance_dmm_substances']['ActiveSubstanceHighLevel'];
            $idIntSub = -1;

            // dump($formData["intervenant_substance_dmm_substances"]);
            // est ce que il n'existe pas déjà cette même substance dans la table IntervenantSubstanceDMMSubstance avec un status "actif" ?
            $isExist = $entityManager->getRepository(IntervenantSubstanceDMM::class)->isSubstanceExistActiv($HL_SA,$idIntSub);

            if($isExist){
                // cette substance existe déjà
                $this->addFlash('error', 'La substance suivante existe déjà avec un statut "actif" : ' . $HL_SA);

                return $this->render('intervenant_substance/liste_intervenant_substance_creation.html.twig', [
                    'IntSub' => $IntSub,
                    'form' => $form->createView(),
                ]);
            }

            $dateNow = new DateTimeImmutable();
            $newEvalua = explode("|", $formData["intervenant_substance_dmm_substances"]["evaluateur"])[0];
            
            $newIntervenant = $intervenantsRepository->findOneBy(['evaluateur' => $newEvalua]);
            
            $IntSub->setEvaluateur($newEvalua);
            $IntSub->setDMM($newIntervenant->getDMM());
            $IntSub->setPoleCourt($newIntervenant->getPoleCourt());
            $IntSub->setPoleLong($newIntervenant->getPoleLong());
            $IntSub->setCreatedAt($dateNow);
            $IntSub->setUpdatedAt($dateNow);

            // dd($IntSub);

            // On traite manuellement les entités IntervenantSubstanceDMMSubstance liées
            foreach ($IntSub->getIntervenantSubstanceDMMSubstances() as $index => $substance) {
                // Mettre à jour le champ updatedAt
                $substance->setUpdatedAt($dateNow);
            
                // Mettre à jour ActiveSubstanceLowLevel et active_substance_high_level
                if (isset($substancesData[$index])) {
                    $substanceData = $substancesData[$index];
                    $substance->setActiveSubstanceLowLevel($substanceData['ActiveSubstanceLowLevel'] ?? null);
                    $substance->setActiveSubstanceHighLevel($substanceData['active_substance_high_level'] ?? null);
                }
            
                if (!$entityManager->contains($substance)) {
                    // L'entité est nouvelle, initialisons createdAt
                    $substance->setCreatedAt($dateNow);
                    $entityManager->persist($substance);
                }
            }            
            $entityManager->persist($IntSub);
            $entityManager->flush();

            return $this->redirectToRoute('app_intervenant_substance');
        
        }

        return $this->render('intervenant_substance/liste_intervenant_substance_creation.html.twig', [
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
