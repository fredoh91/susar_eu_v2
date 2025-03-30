<?php

namespace App\Service;

class ParsingMedicaments
{

    // Variables privées pour stocker les parties de la chaîne
    private $prodSub;
    private $dataMedic;

    /**
     * Divise la chaîne en deux parties : $prodSub et $dataMedic.
     *
     * @param string $input La chaîne à parser.
     * @return void
     */
    private function splitInput($input)
    {
        // Recherche du motif "(S - ", "(I - ", "(C - ", ou "(N - "
        $pattern = '~\s*\((S|I|C|N)\s*-\s*~';
        if (preg_match($pattern, $input, $matches, PREG_OFFSET_CAPTURE)) {
            $splitPos = $matches[0][1]; // Position du motif dans la chaîne
            $this->prodSub = substr($input, 0, $splitPos); // Partie gauche
            $this->dataMedic = substr($input, $splitPos);  // Partie droite
        } else {
            // Si le motif n'est pas trouvé, initialiser les variables à null
            $this->prodSub = null;
            $this->dataMedic = null;
        }
    }

    /**
     * Parse la partie $prodSub pour extraire PRODUIT et SUBSTANCE.
     *
     * @return array|null Retourne un tableau associatif avec 'produit' et 'substance', ou null si aucune correspondance.
     */
    private function parseProduitSubstance()
    {
        if ($this->prodSub === null) {
            return null;
        }
        $pattern = '/^(?<produit>.+?)(?:\s+\[(?<substance>.+?)\])?$/';
        if (preg_match($pattern, trim($this->prodSub), $matches)) {
            return [
                'produit' => trim($matches['produit']),
                'substance' => isset($matches['substance']) ? trim($matches['substance']) : null
            ];
        }
        return null;
    }

    /**
     * Parse la partie $dataMedic pour extraire les autres éléments.
     *
     * @return array|null Retourne un tableau associatif avec les éléments extraits, ou null si aucune correspondance.
     */

    private function parseDataMedic()
    {

        if ($this->dataMedic === null) {
            return null;
        }
        // 1. Enlever les parenthèses extérieures et les espaces superflus
        $input = trim($this->dataMedic);
        $input = trim($input, "()");
        $input = trim($input);

        // 2. Diviser la chaîne principale en 4 parties (avant le crochet)
        $parts = explode(" - [", $input);
        if (count($parts) !== 2) {
            return null; // Format incorrect
        }

        $part1 = $parts[0];
        $part2 = $parts[1];

        // 3. Extraire les informations de la première partie
        $sub_parts1 = explode(" - ", $part1);
        if (count($sub_parts1) !== 3) {
            return null; // Format incorrect
        }

        $drug_char = trim($sub_parts1[0]);
        $indication_pt = trim($sub_parts1[1]);
        $action_taken = trim($sub_parts1[2]);

        // 4. Extraire les informations entre crochets
        $part2 = rtrim($part2, "]"); // Enlever le crochet fermant
        $sub_parts2 = explode(" - ", $part2);

        if (count($sub_parts2) < 4) {
            return null; // Format incorrect
        }

        $start_date = trim($sub_parts2[0]);
        $duration = trim($sub_parts2[1]);
        $dose = trim($sub_parts2[2]);
        $route = trim($sub_parts2[3]);
        $comment = isset($sub_parts2[4]) ? trim($sub_parts2[4]) : null;

        return [
            'drug_char' => $drug_char,
            'indication_pt' => $indication_pt,
            'action_taken' => $action_taken,
            'start_date' => $start_date,
            'duration' => $duration,
            'dose' => $dose,
            'route' => $route,
            'comment' => $comment,
        ];
    }

    /**
     * Méthode publique pour parser la chaîne et retourner les données.
     *
     * @param string $input La chaîne à parser.
     * @return array|null Retourne un tableau associatif avec toutes les données parsées, ou null si aucune correspondance.
     */
    public function donneParsing($input)
    {

        // Étape 1 : Diviser la chaîne en $prodSub et $dataMedic
        $this->splitInput($input);

        // Étape 2 : Parser $prodSub pour extraire PRODUIT et SUBSTANCE
        $produitSubstance = $this->parseProduitSubstance();

        // Étape 3 : Parser $dataMedic pour extraire les autres éléments
        $dataMedic = $this->parseDataMedic();
        // var_dump($dataMedic);
        // Combiner les résultats
        if ($produitSubstance !== null && $dataMedic !== null) {
            return array_merge($produitSubstance, $dataMedic);
        } else {
            return null; // Aucune correspondance trouvée
        }
    }
    public function afficheParsing($input)
    {

        echo "<pre>";
        echo var_dump($input) . "\n";
        echo var_dump($this->donneParsing($input)) . "\n";
        echo "</pre>";
    }

    function parseReactionListPT($reactionString)
    {
        // Initialiser un tableau pour stocker les résultats
        $parsedData = [];

        // Utiliser une expression régulière pour extraire les informations
        $pattern = '/^(?<ReactionListPT>[^(]+) \((?<Outcome>[^)]+) - (?<Date>[^ ]+) - (?<Duration>[^)]+)\)$/';

        if (preg_match($pattern, $reactionString, $matches)) {
            // Extraire les valeurs capturées par les groupes nommés
            $parsedData['ReactionListPT'] = trim($matches['ReactionListPT']);
            $parsedData['Outcome'] = trim($matches['Outcome']);
            $parsedData['Date'] = trim($matches['Date']);
            $parsedData['Duration'] = trim($matches['Duration']);
        } else {
            // Retourner un tableau vide ou lancer une exception si le format n'est pas correct
            return [];
        }

        return $parsedData;
    }
    function parseMedHist($reactionString)
    {
        // Initialiser un tableau pour stocker les résultats
        $parsedData = [];

        // Utiliser une expression régulière pour extraire les informations
        // Le champ "Comment" peut contenir des sauts de ligne, donc on utilise le modificateur "s" pour capturer sur plusieurs lignes
        $pattern = '/^(?<Disease>[^(]+) \(\s*(?<Continuing>[^-]+)\s*-\s*(?<Comment>.+)\)$/s';

        if (preg_match($pattern, $reactionString, $matches)) {
            // Extraire les valeurs capturées par les groupes nommés
            $parsedData['Disease'] = trim($matches['Disease']);
            $parsedData['Continuing'] = trim($matches['Continuing']);

            // Supprimer les sauts de ligne dans le champ Comment
            $parsedData['Comment'] = preg_replace('/\s+/', ' ', trim($matches['Comment']));
        } else {
            // Retourner un tableau vide si le format n'est pas correct
            return [];
        }

        return $parsedData;
    }

    // function parseIndication($indicationString)
    // {
    //     // Initialiser un tableau pour stocker les résultats
    //     $parsedData = [];

    //     // Utiliser une expression régulière pour extraire les informations
    //     // Le champ "Comment" peut contenir des sauts de ligne, donc on utilise le modificateur "s" pour capturer sur plusieurs lignes
    //     $pattern = '/^(?<Disease>[^(]+) \(\s*(?<Continuing>[^-]+)\s*-\s*(?<Comment>.+)\)$/s';

    //     if (preg_match($pattern, $reactionString, $matches)) {
    //         // Extraire les valeurs capturées par les groupes nommés
    //         $parsedData['Disease'] = trim($matches['Disease']);
    //         $parsedData['Continuing'] = trim($matches['Continuing']);

    //         // Supprimer les sauts de ligne dans le champ Comment
    //         $parsedData['Comment'] = preg_replace('/\s+/', ' ', trim($matches['Comment']));
    //     } else {
    //         // Retourner un tableau vide si le format n'est pas correct
    //         return [];
    //     }

    //     return $parsedData;
    // }

    function parseIndication($inputString)
    {
        // Initialiser un tableau pour stocker les résultats
        $parsedData = [];

        // Diviser la chaîne en deux parties autour du séparateur " - "
        $parts = explode(" - ", $inputString, 2);

        if (count($parts) === 2) {
            // Assigner les parties à leurs clés respectives
            $parsedData['product_name'] = trim($parts[0]);
            $parsedData['product_indications_eng'] = trim($parts[1]);
        } else {
            // Si le format est incorrect, retourner un tableau vide
            return [];
        }

        return $parsedData;
    }
}
