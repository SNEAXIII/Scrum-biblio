<?php

namespace App\Form;

abstract class MediaForm
{
    private ?string $titre;
    private ?string $dateCreation;


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

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }



}