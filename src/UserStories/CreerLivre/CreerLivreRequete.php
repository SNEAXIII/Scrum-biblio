<?php

namespace App\UserStories\CreerLivre;

use Symfony\Component\Validator\Constraints as Assert;

class CreerLivreRequete
{
    #[Assert\NotBlank(message: "Le titre doit être renseigné.")]
    public ?string $titre;

    #[Assert\NotBlank(message: "L'ISBN doit être renseigné.")]
    public ?string $isbn;

    #[Assert\NotBlank(message: "L'auteur doit être renseigné.")]
    public ?string $auteur;

    #[Assert\NotNull(message: "Le nombre de pages doit être renseigné.")]
    #[Assert\GreaterThan(value: 0, message: "Le nombre de pages doit être valide.")]
    public ?int $nombrePages;

    #[Assert\NotBlank(message: "La date de parution doit être renseignée.")]
    public ?string $dateCreation;

    /**
     * @param string|null $titre
     * @param string|null $isbn
     * @param string|null $auteur
     * @param int|null $nombrePages
     * @param string|null $dateCreation
     */
    public function __construct(?string $titre, ?string $isbn, ?string $auteur, ?string $dateCreation,?int $nombrePages = null)
    {
        $this -> titre = $titre;
        $this -> isbn = $isbn;
        $this -> auteur = $auteur;
        $this -> nombrePages = $nombrePages;
        $this -> dateCreation = $dateCreation;
    }


    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function getNombrePages(): ?int
    {
        return $this->nombrePages;
    }

    public function getDateCreation(): string
    {
        return $this->dateCreation;
    }


}