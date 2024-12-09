<?php

namespace App\Form;

use App\Entity\SearchSusarEU;
use App\Entity\IntervenantsANSM;
use Doctrine\ORM\EntityRepository;
use App\Entity\IntervenantSubstanceDMM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Repository\IntervenantSubstanceDMMRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
// use App\Repository\IntervenantSubstanceDmmRepository;

class SearchSusarEUType extends AbstractType
{
    private $intervenantRepository;


    public function __construct(IntervenantSubstanceDMMRepository $intervenantRepository)
    {
        $this->intervenantRepository = $intervenantRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $DmmPoleChoices = $this->intervenantRepository->findConcatenatedDmmAndPoleCourt();
        $EvaluateurChoices = $this->intervenantRepository->findEvaluateur();

        $builder
            ->add(
                'specificcaseid', 
                TextType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            ->add(
                'idSusar', 
                IntegerType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            // ->add(
            //     'idSusar', 
            //     IntegerType::class, 
            //     [
            //         'required' => false,
            //         // 'attr' => ['class' => 'chpRq'],
            //         'label' => 'ID', // Vous pouvez personnaliser le label si nécessaire
            //         'attr' => [
            //             'min' => 1, // Valeur minimale autorisée (optionnel)
            //             // 'max' => PHP_INT_MAX, // Valeur maximale autorisée (optionnel)
            //             'step' => 1, // Pas d'incrémentation (optionnel)
            //         ],
            //     ])

            ->add(
                'dmmPoleChoice', 
                ChoiceType::class, 
                [
                    'choices' => $DmmPoleChoices,
                    'required' => false,
                ])
            ->add(
                'evaluateurChoice', 
                ChoiceType::class, 
                [
                    'choices' => $EvaluateurChoices,
                    'required' => false,
                ])
            ->add(
                'debutDateImport', 
                DateType::class, 
                [
                    'widget' => 'single_text',
                    'label' => 'début de date d\'import dans SUSAR_EU : ',
                    'format' => 'yyyy-MM-dd',
                    // 'input' => 'string',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            ->add(
                'finDateImport', 
                DateType::class, 
                [
                    'widget' => 'single_text',
                    'label' => 'fin de date d\'import dans SUSAR_EU : ',
                    'format' => 'yyyy-MM-dd',
                    // 'input' => 'string',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            // ->add('productName', TextType::class, [
            //     'required' => false,
            //     // 'attr' => ['class' => 'chpRq'],
            // ])
            ->add(
                'substanceName', 
                TextType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            ->add(
                'effetIndesirable', 
                TextType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            ->add(
                'narratif', 
                TextType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            // ->add('intervenantANSM', EntityType::class, [
            //     'class' => IntervenantSubstanceDMM::class,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('int')
            //             ->where('int.inactif = 0')
            //             ->orderBy('int.evaluateur', 'ASC');
            //     },
            //     'choice_label' => 'DMM_pole_court',
            //     'required' => false,
            //     'attr' => ['class' => 'chpRq'],
            // ])
            ->add('niveau1', CheckboxType::class, [
                'label' => 'Niveau 1',
                'required' => false,
            ])
            ->add('niveau2a', CheckboxType::class, [
                'label' => 'Niveau 2a',
                'required' => false,
            ])
            ->add('niveau2b', CheckboxType::class, [
                'label' => 'Niveau 2b',
                'required' => false,
            ])
            ->add('niveau2c', CheckboxType::class, [
                'label' => 'Niveau 2c',
                'required' => false,
            ])
            ->add('casTraite', ChoiceType::class, [
                'label' => 'Cas traité',
                'choices' => [
                    '' => '',
                    'Oui' => 'oui',
                    'Non' => 'non',
                ],
                'required' => false,
                'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire
            ])
            ->add('casArchive', ChoiceType::class, [
                'label' => 'Cas archivé',
                'choices' => [
                    'Uniquement les cas archivés' => 'archive',
                    'Uniquement les cas non-archivés' => 'non_archive',
                    'Tous les cas' => 'tous',
                ],
                'required' => false,
                'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire,
                'data' => 'non_archive', // Spécifie 'non_archive' comme valeur par défaut
            ])
            ->add('assessment_outcome', ChoiceType::class, [
                'choices' => [
                    'Screened without action' => 'Screened without action',
                    'Assessed without action' => 'Assessed without action',
                    'Under assessment' => 'Under assessment',
                    'Monitor' => 'Monitor',
                    'Concern in CT' => 'Concern in CT',
                    'À garder en mémoire' => 'À garder en mémoire',
                ],
                'required' => false,
                // 'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire
            ])
            ->add(
                'worldWide_id', 
                TextType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            ->add(
                'num_eudract', 
                TextType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            ->add(
                'sponsorstudynumb', 
                TextType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ])
            ->add('caseVersion', ChoiceType::class, [
                'label' => 'Case version',
                'choices' => [
                    'Cas initial' => 'cas_initial',
                    'Follow-up' => 'follow_up',
                ],
                'required' => false,
            ])
            ->add('casIME', ChoiceType::class, [
                'label' => 'Cas IME',
                'choices' => [
                    '' => '',
                    'Oui' => 'oui',
                    'Non' => 'non',
                ],
                'required' => false,
                'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire
            ])
            ->add('casDME', ChoiceType::class, [
                'label' => 'Cas DME',
                'choices' => [
                    '' => '',
                    'Oui' => 'oui',
                    'Non' => 'non',
                ],
                'required' => false,
                'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire
            ])
            ->add('casEurope', ChoiceType::class, [
                'label' => 'Cas Europe',
                'choices' => [
                    '' => '',
                    'Oui' => 'oui',
                    'Non' => 'non',
                ],
                'required' => false,
                'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire
            ])
            ->add('type_saMS_Mono', ChoiceType::class, [
                'label' => 'saMS/Mono.',
                'choices' => [
                    '' => '',
                    'saMS' => 'saMS',
                    'Mono' => 'Mono',
                ],
                'required' => false,
                'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire
            ])
            ->add(
                'recherche',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary m-2'],
                    'label' => 'Rechercher',
                    'row_attr' => ['id' => 'recherche'],
                ])
            ->add(
                'reset',
                SubmitType::class,[
                    'attr' => ['class' => 'btn btn-primary m-2'],
                    'label' => 'Reset',
                    'row_attr' => ['id' => 'reset'],
                ])
            ->add(
                'exportExcel',
                SubmitType::class,[
                    'attr' => ['class' => 'btn btn-primary m-2'],
                    'label' => 'Export Excel',
                    'row_attr' => ['id' => 'exportExcel'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => SearchSusarEU::class,
        ]);
    }
}
