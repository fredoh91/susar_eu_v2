<?php

namespace App\Controller;

// use DateTime;
use DateTimeImmutable;
use App\Entity\ActiveSubstanceGrouping;
use App\Entity\IntervenantSubstanceDMM;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UploadExcelActiveSubGroupingType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImportExcelActiveSubGroupingController extends AbstractController
{
    #[Route('/upload_excel_activesubgrouping', name: 'app_upload_excel_active_sub_grouping')]
    public function upload_excel(Request $request, ManagerRegistry $doctrine, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(UploadExcelActiveSubGroupingType::class);
        $form->handleRequest($request);
        $tabActSubGrp = [];

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $FicExcel */
            $FicExcel = $form->get('FicExcel')->getData();

            if ($FicExcel) {
                
                // inactiver tous les enregistrement de la table active_substance_grouping 
                $entityManager = $doctrine->getManager();
                $entityManager->getRepository(ActiveSubstanceGrouping::class)->inactiveTout();

                $inputFileName = './Temp/Active substance grouping.xlsx';
                $inputFileName = $FicExcel->getRealPath();

                $spreadsheet = IOFactory::load($inputFileName);
                $activeWorksheet = $spreadsheet->getActiveSheet();
                $highestRow = $activeWorksheet->getHighestRow();
                // $creationtime = filectime($inputFileName);
                // $creation_Time = new DateTime();
                // $creation_Time->setTimestamp(filectime($inputFileName));
                // dump($creation_Time);
                // $creation_time = filectime($inputFileName);
                $creation_time = DateTimeImmutable::createFromFormat('U', filectime($inputFileName));
                // dump('Heure de création : ' . date('Y-m-d H:i:s', $creation_time));
                $creation_date = new DateTimeImmutable();


                // $creationTime = new DateTime();
                // $creationTime->setTimestamp($FicExcel->getMTime());
                // dump($creationTime);
                for ($row = 2; $row <= $highestRow; $row++) {


                    $AS_HL = $activeWorksheet->getCell('A' . $row)->getFormattedValue();
                    $AS_LL = $activeWorksheet->getCell('B' . $row)->getFormattedValue();

                    
                    $activeSubstanceGrouping = new ActiveSubstanceGrouping;
                    $activeSubstanceGrouping->setActiveSubstanceHighLevel($AS_HL);
                    $activeSubstanceGrouping->setActiveSubstanceLowLevel($AS_LL);
                    $activeSubstanceGrouping->setInactif(false);
                    $activeSubstanceGrouping->setDateFichier($creation_time);
                    $activeSubstanceGrouping->setUtilisateurImport('Frederic.RANNOU@ansm.sante.fr');
                    $activeSubstanceGrouping->setCreatedAt($creation_date);
                    $activeSubstanceGrouping->setUpdatedAt($creation_date);


                    $IntervenantSubstanceDMM = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findByHL_SA($AS_HL);

                    
                    // if (!is_null($IntervenantSubstanceDMM)) {
                        if (count($IntervenantSubstanceDMM) == 0) {
                            // il n'y a pas d'intervenantSubstanceDMM, on ne fait rien

                        } elseif (count($IntervenantSubstanceDMM) == 1) {
                            // il n'y a qu'un seul intervenantSubstanceDMM, on l'ajoute à activeSubstanceGrouping
                            $activeSubstanceGrouping->setIntSubDMM($IntervenantSubstanceDMM[0]);
                            
                        } else {
                            // dd($IntervenantSubstanceDMM);
                            $response = new Response('Impossible d\'effectuer le charment du fichier Excel.<BR>Il existe plusieurs ligne pour le "High Level" suivant, merci de ne laisser qu\'une seule ligne active : ' . 
                                                        $IntervenantSubstanceDMM[0]->getActiveSubstanceHighLevel(), Response::HTTP_NOT_FOUND);
                            $response->send();
                            exit;
                        }
                    // }
                    
                    $em->persist($activeSubstanceGrouping);
                    
                    // // Liaison de cet ActiveSubstanceGrouping à/aux IntervenantSubstanceDMM ayant le même high level substance name
                    // $IntervenantSubstanceDMM = $entityManager->getRepository(IntervenantSubstanceDMM::class)->findByHL_SA($AS_HL);

                    // foreach ($IntervenantSubstanceDMM as $intSubDMM) {
                    //     $intSubDMM->setActSubGrouping($activeSubstanceGrouping);
                    //     $em->persist($intSubDMM);
                    // }



                    // $tabActSubGrp[] = array(
                        //     'AS_HL' => $activeWorksheet->getCell('A' . $row)->getFormattedValue(),
                        //     'AS_LL' => $activeWorksheet->getCell('B' . $row)->getFormattedValue()
                        // );
                        
                }
                $em->flush();
                // $date_unique = date("Ymd");
                $dateHeureUnique = date('Ymd_His');
                // $fileName = $FicExcel->getClientOriginalName();
                $fileName = 'SubActivGrp_' . $dateHeureUnique . '.xlsx';
                $FicExcel->move($this->getParameter('kernel.project_dir') . '/temp/active_substance_grouping_traites/', $fileName);

                $tabActSubGrp = $entityManager->getRepository(ActiveSubstanceGrouping::class)->findByActif();

            }

            // ... persist the $product variable or any other work

            // return $this->redirectToRoute('app_upload_excel_active_sub_grouping');
        }




        return $this->render('import_excel_active_sub_grouping/upload_excel.html.twig', [
            'form' => $form->createView(),
            // 'controller_name' => 'ImportExcelActiveSubGroupingController',
            'tabActSubGrp' => $tabActSubGrp
        ]);
    }


    #[Route('/aff_activesubgrouping', name: 'app_aff_active_sub_grouping')]
    public function aff_activesubgrouping(ManagerRegistry $doctrine, EntityManagerInterface $em): Response
    {

        $tabActSubGrp = [];
        $entityManager = $doctrine->getManager();
        $tabActSubGrp = $entityManager->getRepository(ActiveSubstanceGrouping::class)->findByActif();

        return $this->render('import_excel_active_sub_grouping/aff_active_sub_grouping.html.twig', [
            'tabActSubGrp' => $tabActSubGrp
        ]);
    }


    #[Route('/import_excel_activesubgrouping', name: 'app_import_excel_active_sub_grouping')]
    public function import_excel(): Response
    {
        $inputFileName = './Temp/Active substance grouping.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);
        // Sélectionner la feuille de calcul à lire
        $activeWorksheet = $spreadsheet->getActiveSheet();

        // Lire les données de la feuille de calcul
        $highestRow = $activeWorksheet->getHighestRow();
        // $highestColumn = $activeWorksheet->getHighestColumn();
        $tabActSubGrp = [];
        for ($row = 2; $row <= $highestRow; $row++) {
            $tabActSubGrp[] = array(
                'AS_HL' => $activeWorksheet->getCell('A' . $row)->getFormattedValue(),
                'AS_LL' => $activeWorksheet->getCell('B' . $row)->getFormattedValue()
            );

        }


        return $this->render('import_excel_active_sub_grouping/index.html.twig', [
            'controller_name' => 'ImportExcelActiveSubGroupingController',
            'tabActSubGrp' => $tabActSubGrp
        ]);
    }



}
