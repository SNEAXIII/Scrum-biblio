<?php

namespace App\classes;

use DateTime;

abstract class Media
{
    protected string $titre;
    protected int $dureeEmprunt;
    protected string $status;
    protected DateTime $dateCreation;
    public function __construct()
    {
    }


}