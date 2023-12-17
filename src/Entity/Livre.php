<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;


#[Entity]
class Livre extends Media
{
    #[Column(length: 25)]
    private ?string $isbn;

    #[Column(length: 60)]
    private ?string $auteur;

    #[Column(type: 'integer', nullable: true)]
    private ?int $nombrePages;

    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getIsbn(): string
    {
        return $this -> isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn(string $isbn): void
    {
        $this -> isbn = $isbn;
    }

    /**
     * @return string
     */
    public function getAuteur(): string
    {
        return $this -> auteur;
    }

    /**
     * @param string $auteur
     */
    public function setAuteur(string $auteur): void
    {
        $this -> auteur = $auteur;
    }

    public function getNombrePages(): ?int
    {
        return $this->nombrePages;
    }

    public function setNombrePages(?int $nombrePages): void
    {
        $this->nombrePages = $nombrePages;
    }



}