<?php
namespace App\UserStories\CreerAdherent;
use Symfony\Component\Validator\Constraints as Assert;

class CreerAdherentRequete
{
    #[Assert\NotBlank(message : "Le prenom doit être saisi !")]
    private ?string $prenom;
    #[Assert\NotBlank(message : "Le nom doit être saisi !")]
    private ?string $nom;
    #[Assert\NotBlank(message : "L'email doit etre saisi !")]
    #[Assert\Email(message:  "L'email n'est pas valide !")]
    private ?string $email;

    /**
     * @param string|null $prenom
     * @param string|null $nom
     * @param string|null $email
     */
    public function __construct(?string $prenom, ?string $nom, ?string $email)
    {
        $this -> prenom = $prenom;
        $this -> nom = $nom;
        $this -> email = $email;
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