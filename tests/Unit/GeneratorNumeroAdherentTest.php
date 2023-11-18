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
//    todo ajouter des test ???
    public function Execute_GenereValidNumeroAdherent_True()
    {

        {
            $numeroAdherent = $this->generator->execute();
            echo $numeroAdherent;
            $isTailleEgaleANeuf = strlen($numeroAdherent) == 9;
            $this->assertTrue($isTailleEgaleANeuf,"La taille du numéro doit être égale à 9");
            $isCommenceParABTiret = str_starts_with($numeroAdherent, "AD-");
            $this->assertTrue($isCommenceParABTiret,"Le numéro doit commencer par AD-");
            $isContientNombreBienPlace = preg_match("/[^0-9]/", (substr($numeroAdherent, 3)));
            $this->assertEquals(0,$isContientNombreBienPlace,"La numéro doit contenir des chiffres pour les slots 3 à 9");
        }
    }

    protected function setUp(): void
    {
        $this->generator = new GeneratorNumeroAdherent();
    }
}

