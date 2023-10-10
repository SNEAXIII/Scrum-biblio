<?php

namespace App\classes;

use DateTime;

class Emprunt
{
    private DateTime $dateEmprunt;
    private DateTime $dateRetourEstime;
    private ?DateTime $dateRetourEffectif;
    private Adherent $adherent;
    private Media $media;

    public function __construct()
    {
    }

}