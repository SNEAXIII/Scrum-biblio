<?php

namespace App\Services;


use App\Entity\Media;
use DateTime;
use ReflectionClass;

class NormaliseMedia
{
    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getDateCreation(): DateTime
    {
        return $this->dateCreation;
    }

    public function getTypeMedia(): string
    {
        return $this->typeMedia;
    }
    private int $id;
    private string $titre;
    private string $statut;
    private DateTime $dateCreation;
    private string $typeMedia;

    public function __construct(Media $media)
    {
        $this->id = $media->getId();
        $this->titre = $media->getTitre();
        $this->statut = $media->getStatus();
        $this->dateCreation = $media->getDateCreation();
        $this->typeMedia = (new ReflectionClass($media))->getShortName();
    }
}

