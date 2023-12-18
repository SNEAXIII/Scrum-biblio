<?php

namespace App\UserStories\EmprunterUnMedia;

use App\Entity\Adherent;
use App\Entity\Emprunt;
use App\Entity\Media;
use App\Services\GeneratorNumeroEmprunt;
use App\Services\ValidatorNumeroEmprunt;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmprunterUnMedia
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private ValidatorNumeroEmprunt $validatorNumeroEmprunt;
    private GeneratorNumeroEmprunt $generatorNumeroEmprunt;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     * @param ValidatorNumeroEmprunt $validatorNumeroEmprunt
     * @param GeneratorNumeroEmprunt $generatorNumeroEmprunt
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator, ValidatorNumeroEmprunt $validatorNumeroEmprunt, GeneratorNumeroEmprunt $generatorNumeroEmprunt)
    {
        $this -> entityManager = $entityManager;
        $this -> validator = $validator;
        $this -> validatorNumeroEmprunt = $validatorNumeroEmprunt;
        $this -> generatorNumeroEmprunt = $generatorNumeroEmprunt;
    }


    /**
     * @throws Exception
     */
    public function execute(CreerEmpruntRequete $requete): bool
    {
        $errors = $this -> validator -> validate($requete);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error -> getMessage();
            }
            throw new Exception(implode("<br>", $messages));
        }

        /** @var Media $media */
        $media = $this
            -> entityManager
            -> getRepository(Media::class)
            -> findOneBy(["id" => $requete -> getIdMedia()]);
        $adherent = $this
            -> entityManager
            -> getRepository(Adherent::class)
            -> findOneBy(["numeroAdherent" => $requete -> getNumeroAdherent()]);
        $numeroEmprunt = $this -> generatorNumeroEmprunt -> execute();
        $intDureeEmprunt = $media -> getDureeEmprunt();
        $dateActuelle = new DateTime();
        $dateEstimee = (new DateTime())->modify("+$intDureeEmprunt days");

        $emprunt = new Emprunt();
        $emprunt -> setDateRetourEffectif(null);
        $emprunt -> setDateEmprunt($dateActuelle);
        $emprunt -> setDateRetourEstime($dateEstimee);
        $emprunt -> setAdherent($adherent);
        $emprunt -> setMedia($media);
        $emprunt -> setNumeroEmprunt($numeroEmprunt);

        $this -> entityManager -> persist($emprunt);
        $this -> entityManager -> flush();
        return true;
    }
}