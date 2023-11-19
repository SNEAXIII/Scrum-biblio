<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\InheritanceType;


#[Entity]
#[InheritanceType("JOINED")]
#[DiscriminatorColumn(name: "type", type: "string")]
#[DiscriminatorMap(["livre" => "Livre"])]
abstract class Media
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: "integer")]
    protected int $id;

    #[Column(length: 100)]
    protected string $titre;

    #[Column(type: "integer")]
    protected int $dureeEmprunt;

    #[Column(length: 25)]
    protected string $status;

    #[Column(length: 10)]
    protected string $dateCreation;

    public function __construct()
    {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this -> id;
    }

    /**
     * @return string
     */
    public function getTitre(): string
    {
        return $this -> titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre(string $titre): void
    {
        $this -> titre = $titre;
    }

    /**
     * @return int
     */
    public function getDureeEmprunt(): int
    {
        return $this -> dureeEmprunt;
    }

    /**
     * @param int $dureeEmprunt
     */
    public function setDureeEmprunt(int $dureeEmprunt): void
    {
        $this -> dureeEmprunt = $dureeEmprunt;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this -> status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this -> status = $status;
    }

    /**
     * @return string
     */
    public function getDateCreation(): string
    {
        return $this -> dateCreation;
    }

    /**
     * @param string $dateCreation
     */
    public function setDateCreation(string $dateCreation): void
    {
        $this -> dateCreation = $dateCreation;
    }

}