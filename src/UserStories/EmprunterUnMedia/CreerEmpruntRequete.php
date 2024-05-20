<?php

namespace App\UserStories\EmprunterUnMedia;

use Symfony\Component\Validator\Constraints as Assert;

class CreerEmpruntRequete
{
    #[Assert\NotBlank(message: "Le numéro d'adherent doit être renseigné.")]
    public string $numeroAdherent;

    #[Assert\NotBlank(message: "L'id du média doit être renseigné.")]
    public int $idMedia;

    /**
     * @param string $numeroAdherent
     * @param int $idMedia
     */
    public function __construct(string $numeroAdherent, int $idMedia)
    {
        $this -> numeroAdherent = $numeroAdherent;
        $this -> idMedia = $idMedia;
    }

    public function getNumeroAdherent(): string
    {
        return $this -> numeroAdherent;
    }

    public function getIdMedia(): int
    {
        return $this -> idMedia;
    }

}