<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ModifEvalSusarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('substance', ChoiceType::class, [
                'choices' => [$options['substance'] => $options['substance']],
                'disabled' => true,
                'data' => $options['substance'],
                'required' => false,
            ])
            ->add('pt', ChoiceType::class, [
                'choices' => [$options['pt'] => $options['pt']],
                'disabled' => true,
                'data' => $options['pt'],
                'required' => false,
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
                'data' => $options['assessment_outcome'],
                'required' => false,
            ])
            ->add('comments', TextareaType::class, [
                'label' => 'Commentaire',
                'required' => false,
                'data' => $options['comment'],
                'attr' => [
                    'rows' => 5,
                    'placeholder' => 'Entrez votre commentaire ici...'
                ],
            ])
            ->add('eval', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary m-2'],
                'label' => 'Validation',
                'row_attr' => ['id' => 'recherche'],
            ])
            ->add('reset', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary m-2'],
                'label' => 'Annulation',
                'row_attr' => ['id' => 'reset'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'substance' => null,
            'pt' => null,
            'assessment_outcome' => null,
            'comment' => null,
        ]);
    }
    
    
}
