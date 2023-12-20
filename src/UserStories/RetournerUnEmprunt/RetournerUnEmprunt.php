<?php

namespace App\UserStories\RetournerUnEmprunt;

use App\Entity\Emprunt;
use App\Services\{StatusMedia, ValidateRequest};
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class RetournerUnEmprunt
{
    private EntityManagerInterface $entityManager;
    private ValidateRequest $validateRequest;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidateRequest $validateRequest
     */
    public function __construct(EntityManagerInterface $entityManager, ValidateRequest $validateRequest)
    {
        $this -> entityManager = $entityManager;
        $this -> validateRequest = $validateRequest;
    }

    /**
     * @throws Exception
     */
    public function execute(CreerRetourRequete $requete): bool
    {
        $this -> validateRequest -> execute($requete);

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