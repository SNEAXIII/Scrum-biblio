<?php

namespace App\Form;

abstract class MediaForm
{
    private ?string $titre;


    public function __construct()
    {
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

}