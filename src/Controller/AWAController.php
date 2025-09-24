<?php

namespace App\Controller;

use App\Entity\SusarEU;
use App\Entity\SubstancePt;
use Psr\Log\LoggerInterface;
use App\Entity\SubstancePtEval;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[IsGranted(new Expression('is_granted("ROLE_DMM_EVAL") or is_granted("ROLE_SURV_PILOTEVEC")'))]
class AWAController extends AbstractController
{
    // private $logger;

    // public function __construct(LoggerInterface $logger)
    // {
    //     $this->logger = $logger;
    // }
    #[Route('/awa_lst_SA_PT/{idsusar}/{type_page_origine}', name: 'app_awa_lst_SA_PT')]
    public function lst_SA_PT(int $idsusar, string $type_page_origine, ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $Susar = $entityManager->getRepository(SusarEU::class)->findOneById($idsusar);
        $SubPTs = $Susar->getSubstancePts();
        // $intSubDmms = $Susar->getIntervenantSubstanceDMMs();


        $nbIntSub = $entityManager->getRepository(SusarEU::class)->nbIntSubDMM($idsusar);

        if ($nbIntSub > 1) { 
            // on affiche une page demandant confirmation pour réaliser l'évaluation
            return $this->render('awa/popup_eval_multiple.html.twig', [
                'idsusar' => $idsusar,
                'Susar' => $Susar,
                'SubPTs' => $SubPTs,
                'nbIntSub' => $nbIntSub,
                'type_page_origine' => $type_page_origine,
            ]);
        } else {
            // on crée les évaluations
            return $this->redirectToRoute('app_awa_crea_eval', [
                'idsusar' => $idsusar,
                'type_page_origine' => $type_page_origine,
            ]);
        }

        return $this->render('awa/liste_couple_substance_EI.html.twig', [
            'idsusar' => $idsusar,
            'Susar' => $Susar,
            'SubPTs' => $SubPTs,
            'nbIntSub' => $nbIntSub,
        ]);
    }
    #[Route('/awa_crea_eval/{idsusar}/{type_page_origine}', name: 'app_awa_crea_eval')]
    public function Crea_AWA(int $idsusar, string $type_page_origine, ManagerRegistry $doctrine, Request $request, LoggerInterface $logger, AuthenticationUtils $authenticationUtils): Response
    {
        // $session = $request->getSession();
        $this->logger = $logger;
        $entityManager = $doctrine->getManager();
        $Susar = $entityManager->getRepository(SusarEU::class)->findOneById($idsusar);
        // 1/ Recherche des SubstancePt pour ce susar
        $SubPTs = $Susar->getSubstancePts(); // phpcs:ignore Squiz.NamingConventions.ValidVariableName.NotCamelCaps
        $dateModif = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'));
        // $lastUsername = $authenticationUtils->getLastUsername();
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        if ($user) {
            $userName = $user->getUserName(); // Appelle la méthode getUserName() de l'entité User
            // dd($userName); // Affiche le userName pour vérifier
        } else {
            throw $this->createAccessDeniedException('Utilisateur non connecté.');
        }        
        $evalCree = false;
        // dump("un : " . $session->get('search_susar_eu'));
        // dump($Susar);
        // dd($Susar);
        if (!$SubPTs->isEmpty()) {
            // La collection n'est pas vide
            // Ajoutez ici le code à exécuter si la collection n'est pas vide
            foreach ($SubPTs as $subPT) {
                // On vérifie qu'il n'existe pas déjà une évaluation pour ce couple Substance/PT pour ce SUSAR
                $existingEval = $entityManager
                                ->getRepository(SubstancePtEval::class)
                                ->findExistingEval($Susar, $subPT);

                if ($existingEval === null) {
                    // 2/ Création des SubstancePtEval - creation d'un objet SubstancePtEval
                    $substancePtEval = new SubstancePtEval();
                    $substancePtEval->setDateEval($dateModif);
                    $substancePtEval->setCreatedAt($dateModif);
                    $substancePtEval->setUpdatedAt($dateModif);
                    $substancePtEval->setUserCreate($userName);
                    $substancePtEval->setUserModif($userName);
                    $substancePtEval->setAssessmentOutcome("Assessed without action");
                    $substancePtEval->setComments("Assessed without action (bouton AWA)");
                    // 3/ Création des liens SubstancePtEval vers SubstancePt
                    $substancePtEval->addSubstancePt($subPT);
                    // 4/ Création des liens SubstancePtEval vers SusarEU
                    $substancePtEval->addSusarEUs($Susar);

                    $entityManager->persist($substancePtEval);
                    
                    if ($Susar->getDateEvaluation() === null) {
                        $Susar->setDateEvaluation($dateModif);
                    } else {

                    }
                    $evalCree = true;

                } else {
                    // Une évaluation pour ce couple Susar/SubstancePt existe déjà
                    $this->addFlash('success', 'Une évaluation existe déjà pour ce susar, il ne peut etre évalué.');
                }
            }
            if ($evalCree) {
                $entityManager->flush();
                $this->addFlash('success', 'Votre évaluation a bien été prise en compte pour le susar ayant l\'idSusar ' . $idsusar . '.');
                if ($type_page_origine === 'PAGE_RECHERCHE_SUSARS') {
                    return $this->redirectToRoute('app_liste_susar_eu');
                } elseif ($type_page_origine === 'PAGE_AUTRES_FU') {
                    return $this->redirectToRoute('app_autres_FU', [
                        'idsusar' => $idsusar,
                    ]);
                } else {
                    return $this->redirectToRoute('app_liste_susar_eu');
                }
            }

        } else {

            // Ce susar n'a pas de SubstancePt, c'est étrange, il faut loguer cette erreur
            $this->logger->error('Le susar ayant l\'idSusar suivant ' . $idsusar . ' n\'a pas de SubstancePt.');
        }

        if ($type_page_origine === 'PAGE_RECHERCHE_SUSARS') {
            return $this->redirectToRoute('app_liste_susar_eu');
        } elseif ($type_page_origine === 'PAGE_AUTRES_FU') {
            return $this->redirectToRoute('app_autres_FU', [
                'idsusar' => $idsusar,
            ]);
        } else {
            return $this->redirectToRoute('app_liste_susar_eu');
        }
        // return $this->redirectToRoute('app_liste_susar_eu');
        // return $this->render('awa/ok_pour_creation_eval.html.twig', [
        //     'idsusar' => $idsusar,
        //     'Susar' => $Susar,
        // ]);
    }

    #[Route('/awa_supp_tout_eval/{idsusar}', name: 'app_supp_tout_eval')]
    public function SuppToutesEval(int $idsusar, ManagerRegistry $doctrine, Request $request, LoggerInterface $logger): Response
    {
        $entityManager = $doctrine->getManager();
        $Susar = $entityManager->getRepository(SusarEU::class)->findOneById($idsusar);
        $SubPTs = $Susar->getSubstancePts();
        $evalSupp = false;
        if (!$SubPTs->isEmpty()) {
            // On a au moins un SubstancePt
            foreach ($SubPTs as $subPT) {
                // On itère sur les SubstancePt pour supprimer toutes les évaluations
                $evals = $subPT->getSubstancePtEvals();
                if (!$evals->isEmpty()) {
                    foreach ($evals as $eval) {
                        // On supprime l'évaluation
                        $entityManager->remove($eval);
                    }
                    $evalSupp = true;
                } else {
                    // On n'a pas d'évaluation pour cette SubstancePt
                }
            }
            if ($evalSupp === true) {
                $Susar->setDateEvaluation(null);
                $entityManager->flush();
                $this->addFlash('success', 'Votre évaluation a bien été prise en compte.');
                // return $this->redirectToRoute('app_detail_susar_eu', ['master_id' => $Susar->getMasterId()]);
                return $this->redirectToRoute('app_detail_susar_eu', ['idsusar' => $Susar->getId()]);
            }
        } else {
            // Ce susar n'a pas de SubstancePt, c'est étrange, il faut loguer cette erreur
            // $this->logger->error('Le susar ayant l\'idSusar suivant ' . $idsusar . ' n\'a pas de SubstancePt.');
        }
    }
}
