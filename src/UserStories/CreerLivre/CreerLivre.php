<?php

namespace App\UserStories\CreerLivre;

use App\Entity\Livre;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerLivre
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
    public function execute(CreerLivreRequete $requete): bool
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
        // Vérifier que l'isbn n'existe pas déjà
        $getAdherantByIsbn =
            $this->entityManager->
            getRepository(Livre::class)->
            findOneBy(["isbn" => $requete->getIsbn()]);
        if ($getAdherantByIsbn) {
            throw new Exception("L'isbn existe déja dans la base de données");
        }
        // Créer le livre

        $livre = new Livre();
        $livre->setTitre($requete->getTitre());
        $livre->setIsbn($requete->getIsbn());
        $livre->setAuteur($requete->getAuteur());
        $livre->setNombrePages($requete->getNombrePages());
        $livre->setDateCreation($requete->getDateCreation());
        $livre->setStatus("Nouveau");
        $livre->setDureeEmprunt(21);
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($livre);
        $this->entityManager->flush($livre);
        return true;
    }
}