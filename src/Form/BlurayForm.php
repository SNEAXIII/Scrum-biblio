<?php

namespace App\Form;

class BlurayForm extends MediaForm
{
    private ?string $realisateur;
    private ?int $duree;
    private ?int $anneeSortie;

    public function __construct()
    {
    }

    /**
     * @return string|null
     */
    public function getRealisateur(): ?string
    {
        return $this -> realisateur;
    }

    /**
     * @param string|null $realisateur
     */
    public function setRealisateur(?string $realisateur): void
    {
        $this -> realisateur = $realisateur;
    }

    /**
     * @return int|null
     */
    public function getDuree(): ?int
    {
        return $this -> duree;
    }

    /**
     * @param int|null $duree
     */
    public function setDuree(?int $duree): void
    {
        $this -> duree = $duree;
    }

    /**
     * @return int|null
     */
    public function getAnneeSortie(): ?int
    {
        return $this -> anneeSortie;
    }

    /**
     * @param int|null $anneeSortie
     */
    public function setAnneeSortie(?int $anneeSortie): void
    {
        $this -> anneeSortie = $anneeSortie;
    }


}