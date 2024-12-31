<?php

namespace App\Controller;

use App\Entity\SusarEU;
use App\Form\DetailSusarEuType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(new Expression('is_granted("ROLE_DMM_EVAL") or is_granted("ROLE_SURV_PILOTEVEC")'))]
class SusarEuDetailController extends AbstractController
{
    #[Route('/detail_susar_eu/{master_id}', name: 'app_detail_susar_eu')]
    public function detail_susar_eu(int $master_id, ManagerRegistry $doctrine, Request $request): Response
    {
        $entityManager = $doctrine->getManager();
        $Susar = $entityManager->getRepository(SusarEU::class)->findSusarByMasterId($master_id);
        // $lastUsername = $authenticationUtils->getLastUsername();
        // $form = $this->createForm(EvalSusarType::class, $Susar);
        $form = $this->createForm(DetailSusarEuType::class, $Susar);
        // $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $Susar->setDateEvaluation(new \DateTime());
// // dump($lastUsername);

//             $Susar->setUtilisateurEvaluation($lastUsername);
// // dd( $Susar);
//             $em->persist($Susar);
//             $em->flush();
//             $this->addFlash('success', 'Votre évaluation a bien été prise en compte, vous pouvez fermer cet onglet.');
//             return $this->redirectToRoute('app_affiche_eval_susar_post_saisie', [
//                 'master_id' => $master_id,
//             ]); 
//         }

        return $this->render('affiche_susar_eu/susar_eu_detail.html.twig', [
            'Susar' => $Susar,
            'form' => $form->createView(),
        ]);
    }
}
