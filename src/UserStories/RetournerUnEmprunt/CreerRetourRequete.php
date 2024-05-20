<?php

namespace App\UserStories\RetournerUnEmprunt;

use Symfony\Component\Validator\Constraints as Assert;

class CreerRetourRequete
{
    #[Assert\NotBlank(message: "Le numéro d'emprunt doit être renseigné.")]
    #[Assert\Regex(
        pattern: "/(^EM-)(\d{9}$)/",
        message: "Le numéro d'emprunt doit être correctement écrit (EM-XXXXXXXXX)"
    )]
    public string $numeroEmprunt;

    /**
     * @param string $numeroEmprunt
     */
    public function __construct(string $numeroEmprunt)
    {
        $this -> numeroEmprunt = $numeroEmprunt;
    }

    public function getNumeroEmprunt(): string
    {
        return $this -> numeroEmprunt;
    }

}