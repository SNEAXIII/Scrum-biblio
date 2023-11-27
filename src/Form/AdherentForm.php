<?php

namespace App\Form;

class AdherentForm
{
    private ?string $nom;
    private ?string $prenom;
    private ?string $email;

    public function __construct()
    {
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this -> nom;
    }

    /**
     * @param string|null $nom
     */
    public function setNom(?string $nom): void
    {
        $this -> nom = $nom;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this -> prenom;
    }

    /**
     * @param string|null $prenom
     */
    public function setPrenom(?string $prenom): void
    {
        $this -> prenom = $prenom;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this -> email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void
    {
        $this -> email = $email;
    }


}