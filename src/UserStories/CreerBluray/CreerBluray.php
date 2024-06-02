<?php

namespace App\UserStories\CreerBluray;

use App\Entity\Bluray;
use App\Services\DureeEmprunt;
use App\Services\StatusMedia;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerBluray
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @throws Exception
     */
    public function execute(CreerBlurayRequete $requete): bool
    {
        // Valider les données en entrées (de la requête)
        $errors = $this->validator->validate($requete);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }
            throw new Exception(implode("<br>", $messages));
        }
        // Créer le Bluray

        $bluray = new Bluray();
        $bluray->setTitre($requete->getTitre());
        $bluray->setRealisateur($requete->getRealisateur());
        $bluray->setAnneeSortie($requete->getAnneeSortie());
        $bluray->setDuree($requete->getDuree());
        $bluray->setDateCreation(new \DateTime());
        $bluray->setStatus(StatusMedia::NOUVEAU);
        $bluray->setDureeEmprunt(DureeEmprunt::BLURAY);
        // Enregistrer le Bluray en base de données
        $this->entityManager->persist($bluray);
        $this->entityManager->flush($bluray);
        return true;
    }
}