<?php

namespace App\Form;

class MagazineForm extends MediaForm
{
    private ?string $numero;
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

    /**
     * @return string|null
     */
    public function getDatePublication(): ?string
    {
        return $this -> datePublication;
    }

    /**
     * @param string|null $datePublication
     */
    public function setDatePublication(?string $datePublication): void
    {
        $this -> datePublication = $datePublication;
    }



}