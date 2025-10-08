<?php

namespace App\Controller;

use App\Entity\SusarEU;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression('is_granted("ROLE_DMM_EVAL") or is_granted("ROLE_SURV_PILOTEVEC")'))]
class AutresFUController extends AbstractController
{
    // #[Route('/autres_FU/{specificcaseid}', name: 'app_autres_FU')]
    // #[Route('/autres_FU/{EVSafetyReportIdentifier}', name: 'app_autres_FU')]
    #[Route('/autres_FU/{idsusar}', name: 'app_autres_FU')]
    public function affiche_autres_fu(int $idsusar, ManagerRegistry $doctrine, Request $request): Response
    {
        
        $entityManager = $doctrine->getManager();
        // $Susars = $entityManager->getRepository(SusarEU::class)->findSusarByEVSafetyReportIdentifier($EVSafetyReportIdentifier);
        $susarSelect = $entityManager->getRepository(SusarEU::class)->findOneById($idsusar);
        if ($susarSelect) {
            
            $Susars = $entityManager->getRepository(SusarEU::class)->findSusarByWorldWideId($susarSelect->getWorldWideId());
            $NbSusar = count($Susars);
            $WW_id = $susarSelect->getWorldWideId();

            // Vérifier si au moins un SUSAR est partagé par plusieurs évaluateurs
            $isMultiEvaluator = false;
            foreach ($Susars as $susar) {
                if ($susar->getIntervenantSubstanceDMMs()->count() > 1) {
                    $isMultiEvaluator = true;
                    break;
                }
            }
            // Récupérer les listes uniques de substances et PTs pour les menus déroulants
            $substances = [];
            $pts = [];
            foreach ($Susars as $susar) {
                foreach ($susar->getSubstancePts() as $subPt) {
                    $substances[] = $subPt->getActiveSubstanceHighLevel();
                    $pts[] = $subPt->getReactionmeddrapt();
                }
            }
            $substances = array_unique($substances);
            $pts = array_unique($pts);
            sort($substances, SORT_STRING | SORT_FLAG_CASE);
            sort($pts, SORT_STRING | SORT_FLAG_CASE);

            return $this->render('autres_fu/liste_autres_fu.html.twig', [
                'Susars' => $Susars,
                // 'TousSusars' => $TousSusars, // Requête contenant les données à paginer
                'NbSusar' => $NbSusar,
                'WW_id' => $WW_id,
                'substances' => $substances,
                'pts' => $pts,
                'isMultiEvaluator' => $isMultiEvaluator,
            ]);
        }
    }
}
