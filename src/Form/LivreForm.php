<?php

namespace App\Form;

class LivreForm extends MediaForm
{
    private ?string $isbn;
    private ?string $auteur;
    private ?int $nombrePages;

    public function __construct()
    {
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): void
    {
        $this->auteur = $auteur;
    }

    public function getNombrePages(): int
    {
        return $this->nombrePages;
    }

    public function setNombrePages(int $nombrePages): void
    {
        $this->nombrePages = $nombrePages;
    }

}