<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\SusarEU;
use App\Entity\SubstancePt;
use App\Form\EvalSusarType;
use Psr\Log\LoggerInterface;
use App\Entity\SubstancePtEval;
use App\Form\ModifEvalSusarType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[IsGranted(new Expression('is_granted("ROLE_DMM_EVAL") or is_granted("ROLE_SURV_PILOTEVEC")'))]
class EvalSusarController extends AbstractController
{
    #[Route('/eval_susar/{idsusar}', name: 'app_eval_susar')]
    public function evalSusar(int $idsusar, ManagerRegistry $doctrine, Request $request, LoggerInterface $logger, AuthenticationUtils $authenticationUtils): Response
    {
        
        $this->logger = $logger;
        $entityManager = $doctrine->getManager();
        $Susar = $entityManager->getRepository(SusarEU::class)->findOneById($idsusar);
        // 1/ Recherche des SubstancePt pour ce susar
        $SubPTs = $Susar->getSubstancePts();
        $substances = [];
        $PTs = [];
        
        foreach ($SubPTs as $SubPT) {
            $substances[] = $SubPT->getActiveSubstanceHighLevel();
            $PTs[] = $SubPT->getReactionmeddrapt();
        }
        
        // Éliminer les doublons
        $substances = array_unique($substances);
        $PTs = array_unique($PTs);
        
        // Trier par ordre alphabétique
        sort($substances, SORT_STRING | SORT_FLAG_CASE);
        sort($PTs, SORT_STRING | SORT_FLAG_CASE);
        

        $form = $this->createForm(EvalSusarType::class, null, [
            'substances' => $substances,
            'pts' => $PTs,
        ]);
        
        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $data = $form->getData();
        //     // Traitez les données soumises ici
        //     // $selectedSubstance = $data['substance'];
        //     // $selectedPT = $data['pt'];
        // }
        // dump($substances);
        // dump($PTs);
        if ($form->isSubmitted()) {
            $formData = $request->request->all();

            if (isset($formData['eval_susar']['reset'])) {
                // dump(1);
                // L'utilisateur a cliqué sur le bouton 'Annulation'
                // return $this->redirectToRoute('app_eval_susar',['idsusar' => $idsusar]);
                // return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);
                return $this->redirectToRoute('app_detail_susar_eu', ['idsusar' => $Susar->getId()]);
                // $TousSusars = $entityManager->getRepository(SusarEU::class)->findAll();
            } elseif (isset($formData['eval_susar']['eval'])) {
                // L'utilisateur a cliqué sur le bouton 'Validation'
                if ($form->isValid()) {
                    // dump(2);
                    // dd($formData['eval_susar']);
                    // 1/ vérification de ce couple substance/pt n'existe pas déjà pour ce susar avec "$hasLinkedEval"
                    // 2/ si oui : ajout du message flash et redirection : return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);
                    // 3/ si non : creation d'une évaluation $substancePtEval = new SubstancePtEval
                    // 4/ il faut rattacher cette entité au SusarEU en cours ainsi qu'a SubstancePt 
                    // 5/ redirection vers la page du détail du susar : return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);

                    $sub = $formData['eval_susar']['substance'];
                    $PT = $formData['eval_susar']['pt'];
                    $assessment_outcome = $formData['eval_susar']['assessment_outcome'];
                    $comments = $formData['eval_susar']['comments'];
                    $dateModif = new \DateTimeImmutable();
                    $lastUsername = $authenticationUtils->getLastUsername();

                    $hasLinkedEval = $entityManager
                                        ->getRepository(SusarEU::class)
                                        ->hasLinkedSubstancePtEval($idsusar, $sub, $PT);

                    if (!$hasLinkedEval) {
                        // ce couple substance/pt n'existe pas déjà pour ce susar
                        // $subPtEval = new SubstancePtEval;
                        // $subPtEval->setAssessmentOutcome($assessment_outcome);
                        // $subPtEval->setComments($comments);
                        // $subPtEval->setDateEval($dateModif);
    
                        $substancePt = $entityManager
                                        ->getRepository(SubstancePt::class)
                                        ->findByActiveSubstanceAndReactionMeddraPt($sub, $PT);
                        if ($substancePt) {
                            // ce SubstancePT existe
                            // dump($substancePt);
                            $isLinkedSubPtSusarEu = $entityManager
                                        ->getRepository(SubstancePt::class)
                                        ->isLinkedToSusarEU($substancePt, $Susar);
                            if ($isLinkedSubPtSusarEu) {
                                // dd($isLinkedSubPtSusarEu);
                            } else {
                                $substancePt->addSusarEUs($Susar);
                                $entityManager->persist($substancePt);
                            }
                        } else {
                            
                            // ce SubstancePT n'existe pas
                            $substancePt = new SubstancePt;
                            $substancePt->setActiveSubstanceHighLevel($sub);
                            $substancePt->setReactionmeddrapt($PT);
                            $substancePt->setActiveSubstanceHighLevel($sub);
                            $substancePt->setCreatedAt($dateModif);
                            $substancePt->setUpdatedAt($dateModif);
                            // $substancePt->setUserCreation($lastUsername);
                            // $substancePt->setUserModif($lastUsername);
                            $substancePt->addSusarEUs($Susar);
                            $entityManager->persist($substancePt);
                        }
    
                        $substancePtEval = new SubstancePtEval;
                        $substancePtEval->setAssessmentOutcome($assessment_outcome);
                        $substancePtEval->setComments($comments);
                        $substancePtEval->setDateEval($dateModif);
                        $substancePtEval->setCreatedAt($dateModif);
                        $substancePtEval->setUpdatedAt($dateModif);
                        $substancePtEval->setUserCreate($lastUsername);
                        $substancePtEval->setUserModif($lastUsername);
                        
                        // Création des liens SubstancePtEval vers SubstancePt
                        $substancePtEval->addSubstancePt($substancePt);
                        // Création des liens SubstancePtEval vers SusarEU
                        $substancePtEval->addSusarEUs($Susar);
                        $entityManager->persist($substancePtEval);

                        if ($Susar->getDateEvaluation() === null) {
                            $Susar->setDateEvaluation($dateModif);
                            $entityManager->persist($Susar);
                        } 

                        // ici il faut :
                        //  - boucler sur les substances de ce susar : $sub_en_court
                        //  - boucler sur les PT de ce susar : $PT_en_court
                        foreach ($substances as $sub_iter) {
                            // dump($sub_iter);
                            foreach ($PTs as $PT_iter) {
                                // dump($PT_iter);
                                // if ($sub_iter !== $sub && $PT_iter !== $PT) {
                                if (!($sub_iter === $sub && $PT_iter === $PT)) {
                                    //  - si $sub_en_court !== $sub et $PT_en_court !== $PT

                                    $hasLinkedEval_iter = $entityManager
                                                    ->getRepository(SusarEU::class)
                                                    ->hasLinkedSubstancePtEval($idsusar, $sub_iter, $PT_iter);
                                    if (!$hasLinkedEval_iter) {
                                        //  - si !$hasLinkedEval_en_court = $entityManager
                                        //                                      ->getRepository(SusarEU::class)
                                        //                                       ->hasLinkedSubstancePtEval($idsusar, $sub, $PT)

                                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                        //  - alors :
                                        //      - On regarde que ce SubstancePT existe
                                        //          - si oui on le rattache a ce susar, si il ne l'est pas deja
                                        //          - si non on le crée et on le rattache a ce susar
                                        //      - On cree un $substancePtEval rattaché au SubstancePt du dessus et au susar
                                        //          ce $substancePtEval->setAssessmentOutcome('Assessed without action')
                                        $substancePt_iter = $entityManager
                                                        ->getRepository(SubstancePt::class)
                                                        ->findByActiveSubstanceAndReactionMeddraPt($sub_iter, $PT_iter);
                                        if ($substancePt_iter) {
                                            // ce SubstancePT existe
                                            // dump($substancePt);
                                            $isLinkedSubPtSusarEu_iter = $entityManager
                                                        ->getRepository(SubstancePt::class)
                                                        ->isLinkedToSusarEU($substancePt_iter, $Susar);
                                            if ($isLinkedSubPtSusarEu_iter) {
                                                // dd($isLinkedSubPtSusarEu);
                                            } else {
                                                $substancePt_iter->addSusarEUs($Susar);
                                                $entityManager->persist($substancePt_iter);
                                            }
                                        } else {
                                            // ce SubstancePT n'existe pas
                                            $substancePt_iter = new SubstancePt;
                                            $substancePt_iter->setActiveSubstanceHighLevel($sub_iter);
                                            $substancePt_iter->setReactionmeddrapt($PT_iter);
                                            $substancePt_iter->setActiveSubstanceHighLevel($sub_iter);
                                            $substancePt_iter->setCreatedAt($dateModif);
                                            $substancePt_iter->setUpdatedAt($dateModif);
                                            // $substancePt->setUserCreation($lastUsername);
                                            // $substancePt->setUserModif($lastUsername);
                                            $substancePt_iter->addSusarEUs($Susar);
                                            $entityManager->persist($substancePt_iter);
                                        }
                    
                                        $substancePtEval_iter = new SubstancePtEval;
                                        $substancePtEval_iter->setAssessmentOutcome('Assessed without action');
                                        $substancePtEval_iter->setComments('Assessed without action automatic');
                                        $substancePtEval_iter->setDateEval($dateModif);
                                        $substancePtEval_iter->setCreatedAt($dateModif);
                                        $substancePtEval_iter->setUpdatedAt($dateModif);
                                        $substancePtEval_iter->setUserCreate($lastUsername);
                                        $substancePtEval_iter->setUserModif($lastUsername);
                                        
                                        // Création des liens SubstancePtEval vers SubstancePt
                                        $substancePtEval_iter->addSubstancePt($substancePt_iter);
                                        // Création des liens SubstancePtEval vers SusarEU
                                        $substancePtEval_iter->addSusarEUs($Susar);
                                        $entityManager->persist($substancePtEval_iter);

                                        if ($Susar->getDateEvaluation() === null) {
                                            $Susar->setDateEvaluation($dateModif);
                                            $entityManager->persist($Susar);
                                        }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                                    }
                                }
                            }
                        }
                        // dd('fin');

                        // dump($substances);
                        // dump($PTs);
                        $entityManager->flush();
                        $this->addFlash('success', 'l\'évaluation pour le couple ' . $sub . '/'. $PT . ' a bien été pris en compte. Les autres couples SA/PT ont été évalués à "Assessed without action".');
                        // return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);
                        return $this->redirectToRoute('app_detail_susar_eu', ['idsusar' => $Susar->getId()]);
                    } else {
                        // ce couple substance/pt existe déjà pour ce susar
                        $this->addFlash('error', 'Il existe déjà une évaluation pour ce susar avec ce couple ' . $sub . '/'. $PT . ', merci de modifier cette évaluation.');
                        // return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);
                        return $this->redirectToRoute('app_detail_susar_eu', ['idsusar' => $Susar->getId()]);
                    }

                } else {
                    // Formulaire soumis, mais invalide
                    // dump(3);
                }
            } else {
                // dump(4);
            }
            // dd($formData);
        }

        return $this->render('eval_susar/eval_susar.html.twig', [
            'Susar' => $Susar,
            'SubPTs' => $SubPTs,
            'substances' => $substances,
            'PTs' => $PTs,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/supp_eval_susar/{idsusar}/{idSubstancePt}/{idSubstancePtEval}', name: 'app_supp_eval')]
    public function evalSusarSupp(int $idsusar, int $idSubstancePt, int $idSubstancePtEval, ManagerRegistry $doctrine, Request $request, LoggerInterface $logger): Response
    {
        $this->logger = $logger;
        $entityManager = $doctrine->getManager();
        $Susar = $entityManager->getRepository(SusarEU::class)->findOneById($idsusar);
        $SubstancePt = $entityManager->getRepository(SubstancePt::class)->findOneById($idSubstancePt);
        $SubstancePtEval = $entityManager->getRepository(SubstancePtEval::class)->findOneById($idSubstancePtEval);
        $SubstancePtEval->removeSubstancePt($SubstancePt);
        $SubstancePtEval->removeSusarEUs($Susar);
        $entityManager->remove($SubstancePtEval);
        $entityManager->flush();
        $this->addFlash('success', 'l\'évaluation pour le couple ' . $SubstancePt->getActiveSubstanceHighLevel() . '/'. $SubstancePt->getReactionmeddrapt() . ' a bien été supprimée.');
        
        // return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]); 
        return $this->redirectToRoute('app_detail_susar_eu', ['idsusar' => $Susar->getId()]);     
    }


    #[Route('/modif_eval_susar/{idsusar}/{idSubstancePt}/{idSubstancePtEval}', name: 'app_modif_eval')]
    public function evalSusarModif(int $idsusar, 
                                    int $idSubstancePt, 
                                    int $idSubstancePtEval, 
                                    ManagerRegistry $doctrine, 
                                    Request $request, 
                                    LoggerInterface $logger,
                                    AuthenticationUtils $authenticationUtils): Response
    {
        
        $this->logger = $logger;
        $entityManager = $doctrine->getManager();
        $Susar = $entityManager->getRepository(SusarEU::class)->findOneById($idsusar);
        $SubstancePt = $entityManager->getRepository(SubstancePt::class)->findOneById($idSubstancePt);
        $SubstancePtEval = $entityManager->getRepository(SubstancePtEval::class)->findOneById($idSubstancePtEval);
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(ModifEvalSusarType::class, null, [
            'substance' => $SubstancePt->getActiveSubstanceHighLevel() ,
            'pt' => $SubstancePt->getReactionmeddrapt(),
            'assessment_outcome' => $SubstancePtEval->getAssessmentOutcome(),
            'comment' => $SubstancePtEval->getComments(),
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $formData = $request->request->all();
            if (isset($formData['modif_eval_susar']['reset'])) {
                // dump(1);
                // L'utilisateur a cliqué sur le bouton 'Annulation'
                // return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);
                return $this->redirectToRoute('app_detail_susar_eu', ['idsusar' => $Susar->getId()]);
            } elseif (isset($formData['modif_eval_susar']['eval'])) {
                // L'utilisateur a cliqué sur le bouton 'Validation'
                // dump($form->isValid());
                if ($form->isValid()) {
                    // dump(2);
                    $assessment_outcome = $formData['modif_eval_susar']['assessment_outcome'];
                    $comments = $formData['modif_eval_susar']['comments'];
                    $dateModif = new \DateTimeImmutable();

                    $SubstancePtEval->setAssessmentOutcome($assessment_outcome);
                    $SubstancePtEval->setComments($comments);
                    $SubstancePtEval->setDateEval($dateModif);
                    $SubstancePtEval->setUpdatedAt($dateModif);
                    $SubstancePtEval->setUserModif($lastUsername);
                    
                    $entityManager->flush();
                    $this->addFlash('success', 'La modification de l\'évaluation pour le couple ' . 
                                                $SubstancePt->getActiveSubstanceHighLevel() . '/'. 
                                                $SubstancePt->getReactionmeddrapt() . ' a bien été pris en compte.');
                    // return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);
                    return $this->redirectToRoute('app_detail_susar_eu', ['idsusar' => $Susar->getId()]);

                } else {
                    // Formulaire soumis, mais invalide
                    // dump(3);
                }
            } else {
                // dump(4);
            }
        }        
        
        // return $this->render('eval_susar/modif_eval_susar.html.twig', [
        //     'form' => $form->createView(),
        // ]);        
        return $this->render('eval_susar/eval_susar.html.twig', [
            'form' => $form->createView(),
        ]);

        $this->addFlash('success', 'l\'évaluation pour le couple ' . $SubstancePt->getActiveSubstanceHighLevel() . '/'. $SubstancePt->getReactionmeddrapt() . ' a bien été modifiée.');
        
        // return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);
        return $this->redirectToRoute('app_detail_susar_eu', ['idsusar' => $Susar->getId()]);    
    }
}
