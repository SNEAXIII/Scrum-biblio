<?php

namespace App\Tests\Unit;

use App\Services\GeneratorNumeroAdherent;
use Exception;
use PHPUnit\Framework\TestCase;

class GeneratorNumeroAdherentTest extends TestCase
{
    /**
     * @test
     */
    public function Execute_GenereValidNumeroAdherent_True()
    {
//        todo tester ca
        {
            $numeroAdherent = $this->generator->execute();
            $isTailleEgaleANeuf = strlen($numeroAdherent) == 9;
            if ($isTailleEgaleANeuf) {
                throw new Exception("La taille du numéro doit être égale à 9");
            }
            $isCommenceParABTiret = str_starts_with($numeroAdherent, "AD-");
            if ($isCommenceParABTiret) {
                throw new Exception("Le numéro doit commencer par AD-");
            }
            $isContientNombreBienPlace = !preg_match("/[^0-9]/", (substr($numeroAdherent, 3)));
            if ($isContientNombreBienPlace) {
                throw new Exception("La numéro doit contenir des chiffres pour les slots 3 à 9");
            }

        }
    }

    protected function setUp(): void
    {
        $this->generator = new GeneratorNumeroAdherent();
    }
}

