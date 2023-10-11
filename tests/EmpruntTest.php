<?php

namespace App\Tests;

use App\classes\Emprunt;
use PHPUnit\Framework\TestCase;
use DateTime;

function newDate(?int $number = null): DateTime
{
    $dateTime = new DateTime();
    if (isset($number)) {
        return $dateTime->modify("{$number}day");
    }
    return $dateTime;
}

class EmpruntTest extends TestCase
{
    public function testIsEnCours()
    {
        $empruntFictif = new Emprunt();
        $empruntFictif->setDateRetourEstime(newDate(15));
        $empruntFictif->setDateRetourEffectif(null);
        $isTrue = $empruntFictif->isEnCours();
        $empruntFictif->setDateRetourEffectif(newDate(5));
        $isFalse = $empruntFictif->isEnCours();

        self::assertTrue($isTrue);
        self::assertFalse($isFalse);


    }

    public function testIsEnRetard()
    {
        $empruntFictif = new Emprunt();
        // On simule un retard de 9 jours
        $empruntFictif->setDateRetourEstime(newDate(-9));
        // Le media n'est pas rendu
        $empruntFictif->setDateRetourEffectif(null);
        $isTrue = $empruntFictif->isEnRetard();
        // Le media est rendu
        $empruntFictif->setDateRetourEffectif(newDate());
        $isFalse = $empruntFictif->isEnRetard();

        self::assertTrue($isTrue);
        self::assertFalse($isFalse);
    }
}
