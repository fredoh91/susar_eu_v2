<?php
// src/Form/IntervenantSubstanceDMM_detailType.php
namespace App\Form;

use App\Entity\SusarEU;
use App\Entity\IntervenantsANSM;
use App\Entity\ActiveSubstanceGrouping;
use App\Entity\IntervenantSubstanceDMM;
use Symfony\Component\Form\AbstractType;
use App\Repository\IntervenantsANSMRepository;
use App\Entity\IntervenantSubstanceDMMSubstance;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class IntervenantSubstanceDMM_detailType extends AbstractType
{
    
    private $intervenantsRepository;

    public function __construct(IntervenantsANSMRepository $intervenantsRepository)
    {
        $this->intervenantsRepository = $intervenantsRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $intervenantsChoices = $this->intervenantsRepository->getFormattedChoices();
        
        $builder
            ->add('evaluateur', TextType::class,
            [
                'label' => 'Évaluateur : ',
            ])
            ->add('ActiveSubstanceHighLevel', TextType::class,
            [
                'label' => 'Substance active (High level) : ',
                // 'required' => false, // Permet des valeurs nulles
                // 'empty_data' => '', // Utilise une chaîne vide au lieu de null
            ])
            ->add('intervenantSubstanceDMMSubstances', CollectionType::class, [
                'entry_type' => IntervenantSubstanceDMMSubstanceType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => false,
            ])
            ->add('inactif')
            ->add('type_saMS_Mono', ChoiceType::class, [
                'label' => 'Type (saMS/Mono) : ',
                'choices' => [
                    '' => '',
                    'saMS' => 'saMS',
                    'Mono' => 'Mono',
                ],
                'required' => true,
                'placeholder' => false, // Ceci empêche Symfony d'ajouter une option vide supplémentaire   
            ])            
            ->add('AssociationDeSubstances')
            ->add('evaluateur', ChoiceType::class, [
                'choices' => $intervenantsChoices,
                'choice_label' => function($choice, $key, $value) {
                    return explode('|', $value)[0]; // Afficher seulement la première colonne
                },
                // 'placeholder' => 'Select an option',
                'mapped' => false, // Ne pas mapper ce champ directement à l'entité
                'data' => $options['evaluateur_choice'] // Définir la valeur initiale
            ])
            ->add('dmm', TextType::class, [
                'attr' => ['readonly' => true], // Rend ce champ en lecture seule
                'data' => $options['dmm'] // Définir la valeur initiale
            ])
            ->add('pole_court', TextType::class, [
                'attr' => ['readonly' => true], // Rend ce champ en lecture seule
                'data' => $options['pole_court'] // Définir la valeur initiale
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IntervenantSubstanceDMM::class,
            'evaluateur_choice' => null,
            'dmm' => null,
            'pole_court' => null,
        ]);
    }
    public function getBlockPrefix()
    {
        return 'intervenant_substance_dmm_substances';
    }
}
