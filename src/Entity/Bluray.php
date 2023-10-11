<?php

namespace App\Entity;

class Bluray extends Media
{
    private string $realisateur;
    private int $duree;
    private int $anneeSortie;

    public function __construct()
    {
    }

}