<?php

namespace App\classes;

class Livre extends Media
{
    private string $isbn;
    private string $auteur;
    private int $nombrePages;

    public function __construct()
    {
    }

}