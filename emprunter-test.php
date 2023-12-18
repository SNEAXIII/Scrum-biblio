<?php
/**@var EntityManagerInterface $entityManager*/
require "bootstrap.php";

use App\Services\GeneratorNumeroEmprunt;
use App\Services\ValidatorNumeroEmprunt;
use App\UserStories\EmprunterUnMedia\CreerEmpruntRequete;
use App\UserStories\EmprunterUnMedia\EmprunterUnMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ValidatorBuilder;


$emprunterUnMedia = new EmprunterUnMedia(
    $entityManager,
    (new ValidatorBuilder()) -> enableAnnotationMapping() -> getValidator(),
    new ValidatorNumeroEmprunt(),
    new GeneratorNumeroEmprunt($entityManager)
);
$empruntRequete = new CreerEmpruntRequete(
    "AD-999999",
    1
);
$emprunterUnMedia->execute($empruntRequete);