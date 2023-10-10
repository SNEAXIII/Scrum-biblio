<?php

namespace App\classes;

use DateTime;

class Adherent
{
    private string $numeroadherent;
    private string $nom;
    private string $prenom;
    private string $email;
    private ?DateTime $dateAdhesion;

    public function __construct()
    {
    }

}