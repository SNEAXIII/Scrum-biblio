<?php

namespace App\UserStories\RendreUnMediaDisponible;


use App\Entity\Media;
use App\Services\StatusMedia;
use Doctrine\ORM\EntityManagerInterface;

class RendreUnMediaDisponible
{
    private EntityManagerInterface $entityManager;
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    public function execute(int $id): bool
        {
            // Récupérer le média
            /**
             * @var $selectedMedia ?Media
             */
            $selectedMedia = $this -> entityManager
                -> getRepository(Media::class)
                -> findOneBy(["id" => $id]);

            // Vérifier si le média existe
            if (is_null($selectedMedia)) {
                throw new \Exception("Le média sélectionné n'existe pas dans la base de données");
            }

            // Verifier si le média est en statut nouveau
            if ($selectedMedia->getStatus()!==StatusMedia::NOUVEAU) {
                throw new \Exception("Le média sélectionné n'a pas le statut \"Nouveau\"");
            }

            // On rend le livre disponible
            $selectedMedia->setStatus(StatusMedia::DISPONIBLE);

            // On enregistre
            $this -> entityManager->flush();
            return true;
        }
}