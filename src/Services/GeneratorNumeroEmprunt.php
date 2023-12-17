<?php

namespace App\Services;
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

    public function execute(int $oldId): string
    {
        // On génère le numéro suivant
        $actualId = $oldId + 1;

        // On le formate pour qu'il corresponde au format "XXXXXXXXX".
        $numberFormat = sprintf("%'.09d", $actualId);

        // On le concatène au format "EM-XXXXXXXXX".
        return "EM-$numberFormat";
    }
}