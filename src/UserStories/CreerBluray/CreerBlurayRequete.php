<?php

namespace App\UserStories\CreerBluray;

use Symfony\Component\Validator\Constraints as Assert;

class CreerBlurayRequete
{
    #[Assert\NotBlank(message: "Le titre doit être renseigné.")]
    public string $titre;

    #[Assert\NotBlank(message: "Le numéro doit être renseigné.")]
    public string $realisateur;

    #[Assert\NotBlank(message: "La date de publication doit être renseignée.")]
    #[Assert\Type(type: "integer", message: "La durée doit être un nombre.")]
    #[Assert\GreaterThan(value: 0, message: "La durée doit être positive.")]
    public int $duree;

    #[Assert\NotBlank(message: "La date de publication doit être renseignée.")]
    #[Assert\Type(type: "integer", message: "La date de publication doit être un nombre.")]
    public int $anneeSortie;

    /**
     * @param string $titre
     * @param string $realisateur
     * @param int $duree
     * @param int $anneeSortie
     */
    public function __construct(string $titre, string $realisateur, int $duree, int $anneeSortie)
    {
        $this -> titre = $titre;
        $this -> realisateur = $realisateur;
        $this -> duree = $duree;
        $this -> anneeSortie = $anneeSortie;
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
    public function getRealisateur(): string
    {
        return $this -> realisateur;
    }

    /**
     * @return int
     */
    public function getDuree(): int
    {
        return $this -> duree;
    }

    /**
     * @return int
     */
    public function getAnneeSortie(): int
    {
        return $this -> anneeSortie;
    }

}