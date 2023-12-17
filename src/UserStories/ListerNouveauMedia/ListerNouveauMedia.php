<?php

namespace App\UserStories\ListerNouveauMedia;

use App\Entity\Media;
use App\Services\MediaNormalizeArray;
use App\Services\NormaliseMedia;
use App\Services\StatusMedia;
use Doctrine\ORM\EntityManagerInterface;

class ListerNouveauMedia
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function execute(): ?array
    {
        $rawArrayMedia = $this->entityManager
            ->getRepository(Media::class)
            ->findBy(["status" => StatusMedia::NOUVEAU ],$orderBy = ["dateCreation" => "DESC"]);
        return (new MediaNormalizeArray)->execute($rawArrayMedia);
    }
}