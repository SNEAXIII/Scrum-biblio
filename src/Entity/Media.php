<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\OneToMany;


#[Entity]
#[ORM\Table("media")]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "type", type: "string")]
#[DiscriminatorMap(["livre" => "Livre", "magazine" => "Magazine", "bluray" => "Bluray"])]
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

    #[Column(type: 'string')]
    protected ?string $status;

    #[Column(length: 10)]
    protected ?DateTime $dateCreation;

    #[OneToMany(mappedBy: 'media', targetEntity: Emprunt::class)]
    private Collection $emprunts;

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this -> id;
    }

    public function getTitre(): ?string
    {
        return $this -> titre;
    }

    public function setTitre(?string $titre): void
    {
        $this -> titre = $titre;
    }

    public function getDureeEmprunt(): ?int
    {
        return $this -> dureeEmprunt;
    }

    public function setDureeEmprunt(?int $dureeEmprunt): void
    {
        $this -> dureeEmprunt = $dureeEmprunt;
    }

    public function getStatus(): ?string
    {
        return $this -> status;
    }

    public function setStatus(?string $status): void
    {
        $this -> status = $status;
    }

    public function getDateCreation(): ?DateTime
    {
        return $this -> dateCreation;
    }

    public function setDateCreation(?DateTime $dateCreation): void
    {
        $this -> dateCreation = $dateCreation;
    }


}