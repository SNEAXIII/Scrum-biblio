<?php

namespace App\Services;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ValidatorNumeroEmprunt
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
    public function validate(string $numeroEmprunt): bool
    {
        // On prepare les variables
        $patternEM = '/^EM-/';
        $patternNeufChiffres = '/\d{9}$/';
        $sousChaineNeufDernierCaractere = substr($numeroEmprunt, -9);

        // On teste si la taille de la chaine est de 12
        if (strlen($numeroEmprunt) !== 12) {
            throw new Exception("Le numéro d'emprunt doit faire 12 caractères");
        }
        // On teste si les 3 premiers cara sont "EM-"
        if (preg_match($patternEM,$numeroEmprunt)) {
            throw new Exception("Le numéro d'emprunt doit commencer par \"EM-\"");
        }
        // On teste si les 9 derniers cara sont des chiffres
        if (preg_match($patternNeufChiffres,$sousChaineNeufDernierCaractere)) {
            throw new Exception("Le numéro d'emprunt doit finir par 9 chiffres");
        }

        return true;
    }
}