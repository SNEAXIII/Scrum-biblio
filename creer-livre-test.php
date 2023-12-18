<?php
/**@var EntityManagerInterface $entityManager*/
require "bootstrap.php";

use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\RendreUnMediaDisponible\RendreUnMediaDisponible;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ValidatorBuilder;

$validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

$creerLivre = new CreerLivre(
    $entityManager,
    $validator
);


$requete = new CreerLivreRequete(
    "La déchéance humaine2",
    "978-2070368228",
    "johndoe",
    50
);

$creerLivre->execute($requete);

$livreDispo = new RendreUnMediaDisponible($entityManager);

$livreDispo -> execute(1);
