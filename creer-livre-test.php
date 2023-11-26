<?php
require "bootstrap.php";

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
