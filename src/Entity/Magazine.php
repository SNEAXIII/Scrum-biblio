<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Magazine extends Media
{
    #[Column(length: 20)]
    private ?string $numero;

    #[Column(length: 50)]
    private ?string $datePublication;

    public function __construct()
    {
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): void
    {
        $this->numero = $numero;
    }

    public function getDatePublication(): ?string
    {
        return $this->datePublication;
    }

    public function setDatePublication(?string $datePublication): void
    {
        $this->datePublication = $datePublication;
    }



}