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

    #[Assert\NotBlank(message: "La date de creation doit être renseignée.")]
    public string $dateCreation;

    /**
     * @param string $titre
     * @param string $numero
     * @param string $datePublication
     * @param string $dateCreation
     */
    public function __construct(string $titre, string $numero, string $datePublication, string $dateCreation)
    {
        $this -> titre = $titre;
        $this -> numero = $numero;
        $this -> datePublication = $datePublication;
        $this -> dateCreation = $dateCreation;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this -> titre;
    }

    /**
     * @return string
     */
    public function getNumero(): string
    {
        return $this -> numero;
    }

    /**
     * @return string
     */
    public function getDatePublication(): string
    {
        return $this -> datePublication;
    }

    /**
     * @return string
     */
    public function getDateCreation(): string
    {
        return $this -> dateCreation;
    }

}