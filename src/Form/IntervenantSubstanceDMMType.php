<?php

namespace App\Form;

use App\Entity\SusarEU;
use App\Entity\IntervenantsANSM;
use App\Entity\ActiveSubstanceGrouping;
use App\Entity\IntervenantSubstanceDMM;
use Symfony\Component\Form\AbstractType;
use App\Entity\IntervenantSubstanceDMMSubstance;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class IntervenantSubstanceDMMType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('DMM')
            // ->add('pole_long')
            // ->add('pole_court')
            ->add('evaluateur', TextType::class,
            [
                'label' => 'Évaluateur : ',
            ])
            ->add('active_substance_high_level', TextType::class,
            [
                'label' => 'Substance active (High level) : ',
            ])
            ->add('inactif')
            ->add('type_saMS_Mono', TextType::class,
                [
                    'label' => 'Type (saMS/Mono) : ',
                ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('IntervenantANSM', 
                    EntityType::class, [
                'class' => IntervenantsANSM::class,
                'choice_label' => 'DMMPoleCourt',
                'label' => 'DMM-Pôle : ',
            ])
            ->add('AssociationDeSubstances')
            // ->add('susarEUs', EntityType::class, [
            //     'class' => SusarEU::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            // ->add('ActSubGrouping', EntityType::class, [
            //     'class' => ActiveSubstanceGrouping::class,
            //     'choice_label' => 'id',
            // ])

            // ->add('IntervenantSubstanceDMMSubstances', EntityType::class, [
            //     'class' => IntervenantSubstanceDMMSubstance::class,
            //     'choice_label' => 'ActiveSubstanceLowLevel',
            //     'multiple' => true,
            //     'label' => 'Substance active (Low level) : ',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IntervenantSubstanceDMM::class,
        ]);
    }
}
