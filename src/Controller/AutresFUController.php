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
    #[Route('/autres_FU/{specificcaseid}', name: 'app_autres_FU')]
    public function affiche_autres_fu(string $specificcaseid, ManagerRegistry $doctrine, Request $request): Response
    {

        $entityManager = $doctrine->getManager();
        $Susars = $entityManager->getRepository(SusarEU::class)->findSusarBySpecificcaseid($specificcaseid);
        $NbSusar = count($Susars);

        return $this->render('autres_fu/liste_autres_fu.html.twig', [
            'Susars' => $Susars,
            // 'TousSusars' => $TousSusars, // Requête contenant les données à paginer
            'NbSusar' => $NbSusar,
        ]);
    }
}
