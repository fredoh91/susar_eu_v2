<?php

namespace App\Form;

use App\Entity\IntervenantSubstanceDMM;
use Symfony\Component\Form\AbstractType;
use App\Entity\IntervenantSubstanceDMMSubstance;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class IntervenantSubstanceDMMSubstanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('active_substance_high_level', HiddenType::class, [
        ->add('active_substance_high_level', TextType::class, [
            'label' => 'High level substance name',
            'required' => false,
            'attr' => ['readonly' => true], // Rend ce champ en lecture seule
            // 'empty_data' => '', // Utilise une chaîne vide au lieu de null
        ])
            ->add('ActiveSubstanceLowLevel', TextType::class, [
                'label' => 'Low level substance name',
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IntervenantSubstanceDMMSubstance::class,
        ]);
    }
    
    public function getBlockPrefix()
    {
        return 'intervenant_substance_dmm_substances';
    }
}
