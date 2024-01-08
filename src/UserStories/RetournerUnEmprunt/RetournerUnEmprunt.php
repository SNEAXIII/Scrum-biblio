<?php

namespace App\UserStories\RetournerUnEmprunt;

use App\Entity\Emprunt;
use App\Services\{StatusMedia, ValidateRequest};
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RetournerUnEmprunt
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this -> entityManager = $entityManager;
        $this -> validator = $validator;
    }


    /**
     * @throws Exception
     */
    public function execute(CreerRetourRequete $requete): bool
    {

        $errors = $this -> validator -> validate($requete);
        if (count($errors) > 0) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error -> getMessage();
            }

            throw new Exception(implode("<br>", $messages));
        }

        /** @var ?Emprunt $emprunt */
        $emprunt = $this
            -> entityManager
            -> getRepository(Emprunt::class)
            -> findOneBy(["numeroEmprunt" => $requete -> getNumeroEmprunt()]);

        if (is_null($emprunt)) {
            throw new Exception("L'emprunt sélectionné n'existe pas");
        }
        if (!is_null($emprunt -> getDateRetourEffectif())) {
            throw new Exception("Le retour a déjà été effectué");
        }

        $emprunt -> setDateRetourEffectif(new DateTime());

        $emprunt -> getMedia() -> setStatus(StatusMedia::DISPONIBLE);

        $this -> entityManager -> flush();

        return true;
    }
}