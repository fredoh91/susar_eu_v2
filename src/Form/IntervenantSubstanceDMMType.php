<?php

namespace App\Form;

// use App\Entity\SusarEU;
use App\Entity\IntervenantsANSM;
// use App\Entity\ActiveSubstanceGrouping;
use App\Entity\IntervenantSubstanceDMM;
use Symfony\Component\Form\AbstractType;
// use App\Repository\IntervenantsANSMRepository;
// use App\Entity\IntervenantSubstanceDMMSubstance;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
            // ->add('active_substance_high_level', TextType::class,
            // [
            //     'label' => 'Substance active (High level) : ',
            // ])
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

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IntervenantSubstanceDMM::class,
        ]);
    }
}
