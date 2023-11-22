<?php

namespace App\UserStories\CreerLivre;

use Symfony\Component\Validator\Constraints as Assert;

class CreerLivreRequete
{
    #[Assert\NotBlank(message: "Le titre doit être renseigné.")]
    public string $titre;

    #[Assert\NotBlank(message: "L'ISBN doit être renseigné.")]
    public string $isbn;

    #[Assert\NotBlank(message: "L'auteur doit être renseigné.")]
    public string $auteur;

    #[Assert\NotBlank(message: "Le nombre de pages doit être renseigné.")]
    #[Assert\GreaterThan(value: 0, message: "Le nombre de pages doit être valide.")]
    public int $nombrePages;

    #[Assert\NotBlank(message: "La date de parution doit être renseignée.")]
    public string $dateCreation;

    /**
     * @param string $titre
     * @param string $isbn
     * @param string $auteur
     * @param string $dateParution
     * @param int $nombrePages
     */
    public function __construct(string $titre, string $isbn, string $auteur, string $dateParution, int $nombrePages = 0)
    {
        $this->titre = $titre;
        $this->isbn = $isbn;
        $this->auteur = $auteur;
        $this->dateCreation = $dateParution;
        $this->nombrePages = $nombrePages;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @return string
     */
    public function getAuteur(): string
    {
        return $this->auteur;
    }

    /**
     * @return int
     */
    public function getNombrePages(): int
    {
        return $this->nombrePages;
    }

    /**
     * @return string
     */
    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }


}