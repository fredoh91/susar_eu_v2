<?php

namespace App\Form;

use App\Entity\SearchSusarEU;
use App\Entity\IntervenantsANSM;
use Doctrine\ORM\EntityRepository;
use App\Entity\IntervenantSubstanceDMM;
use Symfony\Component\Form\AbstractType;
use App\Repository\IntervenantsANSMRepository;
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
    private $intervenantSubstanceDMMRepository;
    private $intervenantANSMRepository;

    public function __construct(IntervenantSubstanceDMMRepository $intervenantSubstanceDMMRepository, IntervenantsANSMRepository $intervenantANSMRepository)
    {
        $this->intervenantSubstanceDMMRepository = $intervenantSubstanceDMMRepository;
        $this->intervenantANSMRepository = $intervenantANSMRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $DmmPoleChoices = $this->intervenantSubstanceDMMRepository->findConcatenatedDmmAndPoleCourt();
        $EvaluateurAttribue = $this->intervenantSubstanceDMMRepository->findEvaluateur();
        $EvaluateurEvaluation = $this->intervenantANSMRepository->getAllUserChoices();

        $builder
            // Attribution
            ->add(
                'evaluateurAttribue',
                ChoiceType::class,
                [
                    'choices' => $EvaluateurAttribue,
                    'required' => false,
                ]
            )
            ->add(
                'dmmPoleChoice',
                ChoiceType::class,
                [
                    'choices' => $DmmPoleChoices,
                    'required' => false,
                ]
            )

            // Evaluation
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
            ->add('evaluateurEvaluation', ChoiceType::class, [
                'choices' => $EvaluateurEvaluation,
                // 'label' => 'Intervenant ANSM :',
                'required' => false,
                // 'placeholder' => '',
            ])
            ->add('assessment_outcome', ChoiceType::class, [
                // 'choices' => [
                //     'Screened without action' => 'Screened without action',
                //     'Assessed without action' => 'Assessed without action',
                //     'Under assessment' => 'Under assessment',
                //     'Monitor' => 'Monitor',
                //     'Concern in CT' => 'Concern in CT',
                //     'À garder en mémoire' => 'À garder en mémoire',
                // ],
                // 'choices' => [
                //     '7 - Concern in CT' => 'Concern in CT',
                //     '6 - Monitor' => 'Monitor',
                //     '5 - À garder en mémoire' => 'À garder en mémoire',
                //     '4 - Under assessment' => 'Under assessment',
                //     '2 - Assessed without action' => 'Assessed without action',
                //     '1 - Screened without action' => 'Screened without action',
                // ],
                'choices' => [
                    'Concern in CT' => 'Concern in CT',
                    'Monitor' => 'Monitor',
                    'À garder en mémoire' => 'À garder en mémoire',
                    'Under assessment' => 'Under assessment',
                    'Assessed without action' => 'Assessed without action',
                    'Screened without action' => 'Screened without action',
                ],
                'required' => false,
                // 'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire
            ])

            // Essai concerné
            ->add(
                'num_eudract',
                TextType::class,
                [
                    'label' => 'N° EUCT',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )
            ->add(
                'sponsorstudynumb',
                TextType::class,
                [
                    'label' => 'Sponsor',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )
            ->add(
                'paysSurvenue',
                TextType::class,
                [
                    'label' => 'Pays',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )

            // Niveau de classification
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

            // Caractéristiques
            ->add(
                'substanceName',
                TextType::class,
                [
                    'label' => 'Substance active',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )
            ->add(
                'effetIndesirable',
                TextType::class,
                [
                    'label' => 'PT',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )
            ->add(
                'idSusar',
                IntegerType::class,
                [
                    'label' => 'idSUSAR',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )
            ->add(
                'worldWide_id',
                TextType::class,
                [
                    'label' => 'ID cas',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )

            // Période
            ->add(
                'debutGatewayDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'label' => 'début de gateway date : ',
                    'format' => 'yyyy-MM-dd',
                    // 'input' => 'string',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )
            ->add(
                'finGatewayDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'label' => 'fin de gateway date : ',
                    'format' => 'yyyy-MM-dd',
                    // 'input' => 'string',
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )

            // Spécificités
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
            ->add('patientAgeGroup', ChoiceType::class, [
                'label' => 'Population',
                'choices' => [
                    '' => '',
                    'Paediatric' => 'Paediatric',
                    'Adult' => 'Adult',
                    'Geriatric' => 'Geriatric',
                    'Not Specified' => 'Not Specified',
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
            ->add(
                'narratif',
                TextType::class,
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )

            // Cas archivés
            ->add('casArchive', ChoiceType::class, [
                'label' => 'Cas archivé',
                'choices' => [
                    'Uniquement les cas archivés' => 'archive',
                    'Uniquement les cas non-archivés' => 'non_archive',
                    'Tous les cas' => 'tous',
                ],
                'required' => false,
                'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire,
                // 'data' => 'non_archive', // Spécifie 'non_archive' comme valeur par défaut
            ])
            // ->add('caseVersion', ChoiceType::class, [
            //     'label' => 'Case version',
            //     'choices' => [
            //         'Cas initial' => 'cas_initial',
            //         'Follow-up' => 'follow_up',
            //     ],
            //     'required' => false,
            // ])

            // Boutons
            ->add(
                'recherche',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary btn-sm m-1'],
                    'label' => 'Rechercher',
                    'row_attr' => ['id' => 'recherche'],
                ]
            )
            ->add(
                'reset',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary btn-sm m-1'],
                    'label' => 'Reset',
                    'row_attr' => ['id' => 'reset'],
                ]
            )
            ->add(
                'exportExcel',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary btn-sm m-1'],
                    'label' => 'Export Excel',
                    'row_attr' => ['id' => 'exportExcel'],
                ]
            )
        ;
        if ($options['show_import_dates']) {
            $builder

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
                    ]
                )
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
                    ]
                );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => SearchSusarEU::class,
            'show_import_dates' => false,
        ]);
    }
}
