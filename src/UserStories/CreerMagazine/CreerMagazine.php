<?php

namespace App\UserStories\CreerMagazine;

use App\Entity\Magazine;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

const NON_DISPONIBLE = 0;
const NOUVEAU = 1;
const VERIFICATION = 2;
const EMPRUNTE = 3;

class CreerMagazine
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
    public function execute(CreerMagazineRequete $requete): bool
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
        // Créer le livre

        $magazine = new Magazine();
        $magazine->setTitre($requete->getTitre());
        $magazine->setNumero($requete->getNumero());
        $magazine->setDatePublication($requete->getDatePublication());
        $magazine->setDateCreation($requete->getDateCreation());
        $magazine->setStatus(NOUVEAU);
        $magazine->setDureeEmprunt(10);
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($magazine);
        $this->entityManager->flush($magazine);
        return true;
    }
}