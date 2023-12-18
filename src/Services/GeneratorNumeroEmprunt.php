<?php

namespace App\Services;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;

class GeneratorNumeroEmprunt
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
     * @throws Exception
     */
    public function execute(): string
    {

        // On génère le numéro suivant
        $oldId = $this
                -> entityManager
                -> getConnection()
                -> executeQuery("SELECT MAX(id) FROM emprunt")
                -> fetchOne();

        $actualId = $oldId + 1;

        // On le formate pour qu'il corresponde au format "XXXXXXXXX".
        $numberFormat = sprintf("%'.09d", $actualId);

        // On le concatène au format "EM-XXXXXXXXX".
        return "EM-$numberFormat";
    }
}