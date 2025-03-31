<?php

namespace App\Service;

class Priorisation
{

    public function donneNiveauPriorisation_test()
    {
        return 'Niveau 1';
    }
    public function donneNiveauPriorisation(
        $importCtll,
        $casEurope,
        $casIME,
        $casDME
    ) {

        $ageGroup = $importCtll->getAgeGroup();
        $parentChild = $importCtll->getParentChild();
        $seriousnessDeath = $importCtll->getSeriousnessDeath();
        $seriousnessLifethreatening = $importCtll->getSeriousnessLifethreatening();
        $posRechallenge = $importCtll->getPositiveRechallengeForSuspectInteractingDrugs();

        // dump($ageGroup);
        // dump($parentChild);
        // dump($seriousnessDeath);
        // dump($seriousnessLifethreatening);
        // dump($posRechallenge);
        // dump($casEurope);
        // dump($casIME);
        // dump($casDME);

        // critère 2a
        if ($casEurope) {
            if ($seriousnessDeath = 'Yes' || $seriousnessLifethreatening = 'Yes') {
                return 'Niveau 2a';
            }
        }

        //critère 2b
        if ($casEurope) {
            if ($posRechallenge !== 'Not Available' || $ageGroup == 'Paediatric' || $ageGroup == 'Geriatric' || $parentChild == 'Yes' || $casDME) {
                return 'Niveau 2b';
            }
        } else {
            if ($seriousnessDeath == 'Yes' || $seriousnessLifethreatening == 'Yes') {
                return 'Niveau 2b';
            }
        }

        // critère 2c
        if ($casEurope) {
            if ($casIME) {
                return 'Niveau 2c';
            }
        } else {
            if ($posRechallenge !== 'Not Available' || $ageGroup == 'Paediatric' || $ageGroup == 'Geriatric' || $parentChild == 'Yes' || $casIME) {
                return 'Niveau 2c';
            }
        }

        // dd($importCtll);
        return 'Niveau 1';
    }
}
