<?php

namespace App\Services;
class GeneratorNumeroAdherent
{
        public function execute(): string
    {
        // On génère un numéro aléatoire
        $randNumber = rand(0, 999999);

        // On le formate pour qu'il corresponde au format "XXXXXX".
        $numberFormat = sprintf("%'.06d", $randNumber);

        // On le concatène au format "AD-XXXXXX".
        return "AD-$numberFormat";
    }
}