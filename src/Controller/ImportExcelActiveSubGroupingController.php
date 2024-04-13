<?php

namespace App\Controller;

// use DateTime;
use DateTimeImmutable;
use App\Entity\ActiveSubstanceGrouping;
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
        // $tabActSubGrp = [];

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



                    $activeSubstanceGrouping = new ActiveSubstanceGrouping;
                    $activeSubstanceGrouping->setActiveSubstanceHighLevel($activeWorksheet->getCell('A' . $row)->getFormattedValue());
                    $activeSubstanceGrouping->setActiveSubstanceLowLevel($activeWorksheet->getCell('B' . $row)->getFormattedValue());
                    $activeSubstanceGrouping->setInactif(false);
                    $activeSubstanceGrouping->setDateFichier($creation_time);
                    $activeSubstanceGrouping->setUtilisateurImport('Frederic.RANNOU@ansm.sante.fr');
                    $activeSubstanceGrouping->setCreatedAt($creation_date);
                    $activeSubstanceGrouping->setUpdatedAt($creation_date);
                    $em->persist($activeSubstanceGrouping);
                    $em->flush();

                    // $tabActSubGrp[] = array(
                    //     'AS_HL' => $activeWorksheet->getCell('A' . $row)->getFormattedValue(),
                    //     'AS_LL' => $activeWorksheet->getCell('B' . $row)->getFormattedValue()
                    // );
        
                }
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
