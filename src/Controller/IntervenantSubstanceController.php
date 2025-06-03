<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\IntervenantSubstanceDMM;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\IntervenantsANSMRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\IntervenantSubstanceDMM_detailType;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class IntervenantSubstanceController extends AbstractController
{
    #[Route('/intervenant_substance', name: 'app_intervenant_substance')]
    #[IsGranted("ROLE_SURV_PILOTEVEC")]
    public function liste_intervenant_substance(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $repos = $entityManager->getRepository(IntervenantSubstanceDMM::class);
        // $TousIntSub = $repos->findAllSortHL_SA();
        $TousIntSub = $repos->findAllSortHL_SA_sans_non_attribue();
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

    #[Route('/intervenant_substance_nb_susars', name: 'app_intervenant_substance_nb_susars')]
    #[IsGranted("ROLE_SURV_PILOTEVEC")]
    public function liste_intervenant_substance_nb_susars(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $repos = $entityManager->getRepository(IntervenantSubstanceDMM::class);
        // $TousIntSub = $repos->findAllSortHL_SA();
        $TousIntSub = $repos->findAllSortHL_SA_sans_non_attribue();
        $nbActif = $repos->nbIntSub(false);
        $nbInactif = $repos->nbIntSub(true);

        $NbIntSub = count($TousIntSub);

        return $this->render('intervenant_substance/liste_intervenant_substance_nb_susars.html.twig', [
            'TousIntSub' => $TousIntSub,
            'NbIntSub' => $NbIntSub,
            'NbActif' => $nbActif,
            'NbInactif' => $nbInactif,
        ]);
    }

    #[Route('/intervenant_substance/detail/{id}', name: 'app_intervenant_substance_detail')]
    #[IsGranted("ROLE_SURV_PILOTEVEC")]
    public function liste_intervenant_substance_detail(ManagerRegistry $doctrine, int $id): Response
    {
        $entityManager = $doctrine->getManager();
        $IntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findIntSubById($id);

        return $this->render('intervenant_substance/liste_intervenant_substance_detail.html.twig', [
            'IntSub' => $IntSub,
        ]);
    }

    #[Route('/intervenant_substance/modif/{id}', name: 'app_intervenant_substance_modif')]
    #[IsGranted("ROLE_SURV_PILOTEVEC")]
    public function liste_intervenant_substance_modif(ManagerRegistry $doctrine, int $id, IntervenantsANSMRepository $intervenantsRepository, Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $entityManager = $doctrine->getManager();
        $IntSub = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findIntSubById($id);
        if (!$IntSub) {
            $this->addFlash('error', "L'entité IntervenantSubstanceDMM avec l'ID {$id} n'existe pas.");
            return $this->redirectToRoute('app_intervenant_substance');
        }
        $idIntSub= $IntSub->getId();
        // Récupère l'Intervenant ANSM lié à l'entité
        $intervenant = $IntSub->getIntervenantANSM();

        if (!$intervenant) {
            $this->addFlash(
                'error',
                "Aucun Intervenant ANSM n'est lié à cet intervenant/substance. Veuillez vérifier les données."
            );
            return $this->redirectToRoute('app_intervenant_substance');
        }

        $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $dateNow = new DateTimeImmutable();

            $user = $this->getUser(); // Récupère l'utilisateur connecté
            if ($user) {
                $userName = $user->getUserName(); // Appelle la méthode getUserName() de l'entité User
            } else {
                throw $this->createAccessDeniedException('Utilisateur non connecté.');
            }
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
            // Récupère l'entité IntervenantANSM sélectionnée dans le formulaire
            $intervenantANSM = $IntSub->getIntervenantANSM();

            if ($intervenantANSM) {
                $IntSub->setEvaluateur($intervenantANSM->getEvaluateur());
                $IntSub->setDMM($intervenantANSM->getDMM());
                $IntSub->setPoleCourt($intervenantANSM->getPoleCourt());
                $IntSub->setPoleLong($intervenantANSM->getPoleLong());
                $IntSub->setPoleTresCourt($intervenantANSM->getPoleTresCourt());
            } else {
                $this->addFlash('error', "Aucun Intervenant ANSM n'a été sélectionné.");
                return $this->redirectToRoute('app_intervenant_substance');
            }

            $IntSub->setUpdatedAt($dateNow);
            $IntSub->setUserModif($userName);

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
    #[IsGranted("ROLE_SURV_PILOTEVEC")]
    public function liste_intervenant_substance_crea(ManagerRegistry $doctrine, IntervenantsANSMRepository $intervenantsRepository, Request $request, AuthenticationUtils $authenticationUtils): Response
    {
        $entityManager = $doctrine->getManager();
        $IntSub = new IntervenantSubstanceDMM();

        $form = $this->createForm(IntervenantSubstanceDMM_detailType::class, $IntSub);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $formData = $request->request->all();

            $HL_SA = $formData['intervenant_substance_dmm_substances']['ActiveSubstanceHighLevel'];

            // dd($formData['intervenant_substance_dmm_substances']['IntervenantANSM']);

            $intervenantANSMId = $formData['intervenant_substance_dmm_substances']['IntervenantANSM'] ?? null;
            $intervenantANSM = null;
            if ($intervenantANSMId) {
                $intervenantANSM = $intervenantsRepository->find($intervenantANSMId);
            }

            if (!$intervenantANSM) {
                $this->addFlash('error', "L'intervenant ANSM sélectionné est introuvable.");
                return $this->redirectToRoute('app_intervenant_substance');
            }

            $idIntSub = $intervenantANSM->getId();

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

            $user = $this->getUser(); // Récupère l'utilisateur connecté
            if ($user) {
                $userName = $user->getUserName(); // Appelle la méthode getUserName() de l'entité User
            } else {
                throw $this->createAccessDeniedException('Utilisateur non connecté.');
            }

            $IntSub->setEvaluateur($intervenantANSM->getEvaluateur());
            $IntSub->setDMM($intervenantANSM->getDMM());
            $IntSub->setPoleCourt($intervenantANSM->getPoleCourt());
            $IntSub->setPoleLong($intervenantANSM->getPoleLong());
            $IntSub->setPoleTresCourt($intervenantANSM->getPoleTresCourt());
            
            $IntSub->setCreatedAt($dateNow);
            $IntSub->setUpdatedAt($dateNow);
            $IntSub->setUserCreate($userName);
            $IntSub->setUserModif($userName);

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
    #[IsGranted(new Expression('is_granted("ROLE_DMFR_GEST") or is_granted("ROLE_SURV_PILOTEVEC")'))]
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
