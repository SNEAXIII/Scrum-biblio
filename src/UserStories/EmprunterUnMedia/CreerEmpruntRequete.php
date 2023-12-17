<?php

namespace App\UserStories\CreerMagazine;

use Symfony\Component\Validator\Constraints as Assert;

class CreerEmpruntRequete
{
    #[Assert\NotBlank(message: "L'id de l'adherent doit être renseigné.")]
    public int $idAdherent;

    #[Assert\NotBlank(message: "L'id du média doit être renseigné.")]
    public int $idMedia;

    /**
     * @param int $idAdherent
     * @param int $idMedia
     */
    public function __construct(int $idAdherent, int $idMedia)
    {
        $this -> idAdherent = $idAdherent;
        $this -> idMedia = $idMedia;
    }

    /**
     * @return int
     */
    public function getIdAdherent(): int
    {
        return $this -> idAdherent;
    }

    /**
     * @return int
     */
    public function getIdMedia(): int
    {
        return $this -> idMedia;
    }


}