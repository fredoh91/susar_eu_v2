<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class EvalSusarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('substance', ChoiceType::class, [
            'choices' => array_combine($options['substances'], $options['substances']),
            'placeholder' => 'Choisir une substance',
            'required' => true,
        ])
        ->add('pt', ChoiceType::class, [
            'choices' => array_combine($options['pts'], $options['pts']),
            'placeholder' => 'Choisir un PT',
            'required' => true,
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
            'placeholder' => 'Choisir une conclusion',
            'required' => true,
            ])
            ->add('comments', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Entrez votre commentaire ici...'
                ],
            ])
        ->add(
            'eval',
            SubmitType::class,
            [
                'attr' => ['class' => 'btn btn-primary m-2'],
                'label' => 'Validation',
                'row_attr' => ['id' => 'recherche'],
            ]
        )
        ->add(
            'reset',
            SubmitType::class,
            [
                'attr' => [
                    'class' => 'btn btn-primary m-2',
                    'formnovalidate' => 'formnovalidate',
                ],
                'label' => 'Annulation',
                'row_attr' => ['id' => 'reset'],
            ]
        )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'substances' => [],
            'pts' => [],
        ]);
    }
}
