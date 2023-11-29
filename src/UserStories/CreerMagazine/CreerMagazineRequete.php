<?php

namespace App\UserStories\CreerMagazine;

use Symfony\Component\Validator\Constraints as Assert;

class CreerMagazineRequete
{
    #[Assert\NotBlank(message: "Le titre doit être renseigné.")]
    public string $titre;

    #[Assert\NotBlank(message: "Le numéro doit être renseigné.")]
    public string $numero;

    #[Assert\NotBlank(message: "La date de publication doit être renseignée.")]
    public string $datePublication;

    /**
     * @param string $titre
     * @param string $numero
     * @param string $datePublication
     */
    public function __construct(string $titre, string $numero, string $datePublication)
    {
        $this->titre = $titre;
        $this->numero = $numero;
        $this->datePublication = $datePublication;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getNumero(): string
    {
        return $this->numero;
    }

    public function getDatePublication(): string
    {
        return $this->datePublication;
    }


}