<?php

namespace App\classes;

use DateTime;

class Emprunt
{
    private int $id;
    private DateTime $dateEmprunt;
    private DateTime $dateRetourEstime;
    private ?DateTime $dateRetourEffectif;
    private Adherent $adherent;
    private Media $media;

    public function __construct()
    {
    }

    public function isEnCours(): bool
    {
        return !isset($this->dateRetourEffectif);
    }

    public function isEnRetard(): bool
    {
        $isDepasse = $this->dateRetourEstime < new DateTime();
        return ($this->isEnCours() && $isDepasse);
    }

    public function getDateRetourEstime(): DateTime
    {
        return $this->dateRetourEstime;
    }

    public function setDateRetourEstime(DateTime $dateRetourEstime): void
    {
        $this->dateRetourEstime = $dateRetourEstime;
    }

    public function getDateRetourEffectif(): ?DateTime
    {
        return $this->dateRetourEffectif;
    }

    public function setDateRetourEffectif(?DateTime $dateRetourEffectif): void
    {
        $this->dateRetourEffectif = $dateRetourEffectif;
    }
}