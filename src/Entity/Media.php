<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;

//todo fixer les const

#[Entity]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "type", type: "string")]
#[DiscriminatorMap(["livre" => "Livre","magazine" => "Magazine"])]
abstract class Media
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: "integer")]
    protected int $id;

    #[Column(length: 100)]
    protected ?string $titre;

    #[Column(type: "integer")]
    protected ?int $dureeEmprunt;

    #[Column(type: 'integer')]
    protected ?int $status;

    #[Column(length: 10)]
    protected ?DateTime $dateCreation;

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

    public function getDureeEmprunt(): ?int
    {
        return $this->dureeEmprunt;
    }

    public function setDureeEmprunt(?int $dureeEmprunt): void
    {
        $this->dureeEmprunt = $dureeEmprunt;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    public function getDateCreation(): ?DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }



}