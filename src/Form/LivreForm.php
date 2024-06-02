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

    /**
     * @return string|null
     */
    public function getIsbn(): ?string
    {
        return $this -> isbn;
    }

    /**
     * @param string|null $isbn
     */
    public function setIsbn(?string $isbn): void
    {
        $this -> isbn = $isbn;
    }

    /**
     * @return string|null
     */
    public function getAuteur(): ?string
    {
        return $this -> auteur;
    }

    /**
     * @param string|null $auteur
     */
    public function setAuteur(?string $auteur): void
    {
        $this -> auteur = $auteur;
    }

    /**
     * @return int|null
     */
    public function getNombrePages(): ?int
    {
        return $this -> nombrePages;
    }

    /**
     * @param int|null $nombrePages
     */
    public function setNombrePages(?int $nombrePages): void
    {
        $this -> nombrePages = $nombrePages;
    }
}