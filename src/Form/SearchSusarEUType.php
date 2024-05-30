<?php

namespace App\Form;

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
// use App\Repository\IntervenantSubstanceDmmRepository;

class SearchSusarEUType extends AbstractType
{
    private $intervenantRepository;


    public function __construct(
        IntervenantSubstanceDMMRepository $intervenantRepository,
    ) {
        $this->intervenantRepository = $intervenantRepository;
    }
    
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $DmmPoleChoices = $this->intervenantRepository->findConcatenatedDmmAndPoleCourt();
        $EvaluateurChoices = $this->intervenantRepository->findEvaluateur();

        $builder
            ->add(
                'specificcaseid', TextType::class, 
                [
                    'required' => false,
                    // 'attr' => ['class' => 'chpRq'],
                ]
            )
            ->add('dmmPoleChoice', ChoiceType::class, [
                'choices' => $DmmPoleChoices,
                'required' => false,
                // 'placeholder' => 'Select Intervenant',
                // Autres options de configuration
            ])
            ->add('evaluateurChoice', ChoiceType::class, [
                'choices' => $EvaluateurChoices,
                'required' => false,
                // 'placeholder' => 'Select Intervenant',
                // Autres options de configuration
            ])
            ->add('debutDateImport', DateType::class, [
                'widget' => 'single_text',
                'label' => 'début de date d\'import dans SUSAR_EU : ',
                'format' => 'yyyy-MM-dd',
                // 'input' => 'string',
                'required' => false,
                // 'attr' => ['class' => 'chpRq'],
                ])
            ->add('finDateImport', DateType::class, [
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
            ->add('substanceName', TextType::class, [
                'required' => false,
                // 'attr' => ['class' => 'chpRq'],
            ])
            ->add('effetIndesirable', TextType::class, [
                'required' => false,
                // 'attr' => ['class' => 'chpRq'],
            ])
            ->add('narratif', TextType::class, [
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
            ->add(
                'recherche',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary m-2'],
                    'label' => 'Rechercher',
                    'row_attr' => ['id' => 'recherche'],
                ]

            )
            ->add(
                'reset',
                SubmitType::class,
                [
                    'attr' => ['class' => 'btn btn-primary m-2'],
                    'label' => 'Reset',
                    'row_attr' => ['id' => 'reset'],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
