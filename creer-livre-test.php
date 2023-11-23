<?php
require "bootstrap.php";

//todo fix les const
const NON_DISPONIBLE = 0;
const NOUVEAU = 1;
const VERIFICATION = 2;
const EMPRUNTE = 3;

use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use Symfony\Component\Validator\ValidatorBuilder;

$validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

$creerLivre = new CreerLivre(
    $entityManager,
    $validator
);

$requete = new CreerLivreRequete(
    "La déchéance humaine",
    "iiiiiiiii",
    "johndoe",
    "05/07/1984",
    50
);

$creerLivre->execute($requete);
