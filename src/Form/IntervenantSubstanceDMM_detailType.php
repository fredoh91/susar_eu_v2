<?php
// src/Form/IntervenantSubstanceDMM_detailType.php
namespace App\Form;

use App\Entity\IntervenantsANSM;
use App\Entity\IntervenantSubstanceDMM;
use Symfony\Component\Form\AbstractType;
use App\Repository\IntervenantsANSMRepository;
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
            ->add('IntervenantANSM', EntityType::class, [
                'class' => IntervenantsANSM::class, // L'entité liée
                'choice_label' => function (IntervenantsANSM $intervenant) {
                    return  $intervenant->getPrenom() . ' ' . 
                            $intervenant->getNom() . ' (' . 
                            $intervenant->getDMM() . '/' . 
                            $intervenant->getPoleCourt() . ')'; // Affiche le nom et le prénom
                },
                'query_builder' => function (IntervenantsANSMRepository $repo) {
                    return $repo->findActifsQryBld();
                },
                'label' => 'Intervenant ANSM :',
                'attr' => ['readonly' => true], // Rend le champ en lecture seule
                'required' => false, // Permet de ne pas rendre ce champ obligatoire
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
            'idIntSub' => null,
        ]);
    }
    public function getBlockPrefix(): string
    {
        return 'intervenant_substance_dmm_substances';
    }
}
