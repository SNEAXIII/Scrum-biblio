<?php

namespace App\classes;

use DateTime;

class Magazine extends Media
{
    private int $numero;
    private DateTime $datePublication;

    public function __construct()
    {
    }

}