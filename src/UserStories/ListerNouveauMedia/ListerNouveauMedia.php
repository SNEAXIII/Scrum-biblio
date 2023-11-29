<?php

namespace App\UserStories\ListerNouveauMedia;

use App\Entity\Media;
use App\Services\StatusMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\VarDumper\Caster\ClassStub;

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
        return $this->entityManager->getRepository(Media::class)->findBy(["status"=>StatusMedia::NOUVEAU]);
    }
}