<?php

namespace App\Tests\Unit;

use App\Entity\Emprunt;
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
    /**
     * @test
     */
    public function IsEnCours_DateRetourNonRenseignee_True()
    {
        $empruntFictif = new Emprunt();
        $empruntFictif->setDateRetourEstime(newDate(15));
        $empruntFictif->setDateRetourEffectif(null);
        $isTrue = $empruntFictif->isEnCours();

        self::assertTrue($isTrue);
    }

    /**
     * @test
     */
    public function IsEnCours_DateRetourRenseignee_False()
    {
        $empruntFictif = new Emprunt();
        $empruntFictif->setDateRetourEstime(newDate(15));
        $empruntFictif->setDateRetourEffectif(newDate(5));
        $isFalse = $empruntFictif->isEnCours();

        self::assertFalse($isFalse);
    }

    /**
     * @test
     */
    public function IsEnRetard_DateRetourNonRenseignee_True()
    {
        $empruntFictif = new Emprunt();
        // On simule un retard de 9 jours
        $empruntFictif->setDateRetourEstime(newDate(-9));
        // Le media n'est pas rendu
        $empruntFictif->setDateRetourEffectif(null);
        $isTrue = $empruntFictif->isEnRetard();

        self::assertTrue($isTrue);
    }

    /**
     * @test
     */
    public function IsEnRetard_DateRetourNonRenseignee_False()
    {
        $empruntFictif = new Emprunt();
        // On simule un retard de 9 jours
        $empruntFictif->setDateRetourEstime(newDate(-9));
        // Le media est rendu
        $empruntFictif->setDateRetourEffectif(newDate());
        $isFalse = $empruntFictif->isEnRetard();

        self::assertFalse($isFalse);
    }
}
