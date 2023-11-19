<?php
namespace App\UserStories\CreerAdherent;
use Symfony\Component\Validator\Constraints as Assert;

class CreerAdherentRequete
{
    #[Assert\NotBlank(message : "Le prenom doit être saisi !")]
    private string $prenom;
    #[Assert\NotBlank(message : "Le nom doit être saisi !")]
    private string $nom;
    #[Assert\NotBlank(message : "L'email doit etre saisi !")]
    #[Assert\Email(message:  "L'email n'est pas valide !")]
    private string $email;

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $prenom
     * @param string $nom
     * @param string $email
     */
    public function __construct(string $prenom, string $nom, string $email)
    {
        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
    }
}