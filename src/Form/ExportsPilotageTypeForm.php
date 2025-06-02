<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ExportsPilotageTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('extraction', SubmitType::class, [
                'label' => 'Extraction',

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
