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
    protected string $titre;

    #[Column(type: "integer")]
    protected int $dureeEmprunt;

    #[Column(type: 'integer')]
    protected int $status;

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
     * @return int
     */
    public function getStatus(): int
    {
        return $this -> status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
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