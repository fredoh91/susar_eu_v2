<?php

namespace App\Form;

use App\Entity\SusarEU;
use App\Entity\SubstancePt;
use App\Entity\SubstancePtEval;
use App\Entity\IntervenantSubstanceDMM;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DetailSusarEuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('master_id')
            ->add('caseid')
            ->add('specificcaseid')
            ->add('DLPVersion')
            ->add('creationdate', null, [
                'widget' => 'single_text',
            ])
            ->add('statusdate', null, [
                'widget' => 'single_text',
            ])
            ->add('studytitle')
            ->add('sponsorstudynumb')
            ->add('num_eudract')
            ->add('pays_etude')
            ->add('TypeSusar')
            ->add('indication')
            ->add('indication_eng')
            ->add('productName')
            ->add('substanceName')
            ->add('Commentaire')
            ->add('dateEvaluation', null, [
                'widget' => 'single_text',
            ])
            ->add('narratif')
            ->add('pays_survenue')
            ->add('dateAiguillage', null, [
                'widget' => 'single_text',
            ])
            ->add('dateImport', null, [
                'widget' => 'single_text',
            ])
            ->add('NbMedicSuspect')
            ->add('patientAgeGroup')
            ->add('patientAge')
            ->add('patientAgeUnitLabel')
            ->add('isCaseSerious')
            ->add('seriousnessCriteria')
            ->add('patientSex')
            ->add('worldWide_id')
            // ->add('seriousnessCriteria_brut')
            // ->add('utilisateurEvaluation')
            // ->add('utilisateurAiguillage')
            // ->add('utilisateurImport')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            // ->add('intervenantSubstanceDMMs', EntityType::class, [
            //     'class' => IntervenantSubstanceDMM::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            // ->add('substancePts', EntityType::class, [
            //     'class' => SubstancePt::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            // ->add('substancePtEvals', EntityType::class, [
            //     'class' => SubstancePtEval::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
            ->add('Save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary m-2'],
                'label' => 'Sauvegarder']
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SusarEU::class,
        ]);
    }
}
