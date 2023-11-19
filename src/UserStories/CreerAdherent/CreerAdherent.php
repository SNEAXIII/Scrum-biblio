<?php

namespace App\UserStories\CreerAdherent;

use App\Entity\Adherent;
use App\Services\GeneratorNumeroAdherent;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerAdherent
{
    private EntityManagerInterface $entityManager;
    private GeneratorNumeroAdherent $generateurNumeroAdherent;
    private ValidatorInterface $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param GeneratorNumeroAdherent $generateurNumeroAdherent
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, GeneratorNumeroAdherent $generateurNumeroAdherent, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->generateurNumeroAdherent = $generateurNumeroAdherent;
        $this->validator = $validator;
    }


    /**
     * @throws Exception
     */
    public function execute(CreerAdherentRequete $requete): bool
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
        // Vérifier que l'email n'existe pas déjà
        $getAdherantByEmail =
            $this->entityManager->
            getRepository(Adherent::class)->
            findOneBy(["email" => $requete->getEmail()]);
        if ($getAdherantByEmail) {
            throw new Exception("L'email existe déja dans la base de données");
        }
        // Générer un numéro d'adhérent au format AD-999999
        $numeroAdherent = $this->generateurNumeroAdherent->execute();
        // Vérifier que le numéro n'existe pas déjà
        $getAdherantByNumero =
            $this->entityManager->
            getRepository(Adherent::class)->
            findOneBy(["numeroAdherent" => $numeroAdherent]);
        if ($getAdherantByNumero) {
            throw new Exception("Le numéro d'adhérent existe déja dans la base de données");
        }
        // Créer l'adhérent
        $adherent = new Adherent();
        $adherent->setNom($requete->getNom());
        $adherent->setPrenom($requete->getPrenom());
        $adherent->setEmail($requete->getEmail());
        $adherent->setDateAdhesion(new DateTime());
        $adherent->setNumeroAdherent($numeroAdherent);
        // Enregistrer l'adhérent en base de données
        $this->entityManager->persist($adherent);
        $this->entityManager->flush($adherent);
        return true;
    }
}