<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'Adherent')]
class Adherent
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    #[ManyToOne(targetEntity: Emprunt::class)]
    #[JoinColumn(name: 'id')]
    private int $id;

    #[Column(length: 9)]
    private ?string $numeroAdherent;

    #[Column(length: 50)]
    private ?string $nom;

    #[Column(length: 50)]
    private ?string $prenom;

    #[Column(length: 140)]
    private ?string $email;

    #[Column(type: 'date')]
    private ?DateTime $dateAdhesion;

    #[OneToMany(mappedBy: 'adherent', targetEntity: Emprunt::class)]
    private Collection $emprunts;

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNumeroAdherent(): string
    {
        return $this->numeroAdherent;
    }

    public function setNumeroAdherent(string $numeroAdherent): void
    {
        $this->numeroAdherent = $numeroAdherent;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDateAdhesion(): ?DateTime
    {
        return $this->dateAdhesion;
    }

    public function setDateAdhesion(?DateTime $dateAdhesion): void
    {
        $this->dateAdhesion = $dateAdhesion;
    }

}