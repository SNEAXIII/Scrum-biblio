<?php

use App\Entity\Adherent;

require "bootstrap.php";
require "vendor\autoload.php";

$adherant = new Adherent();
$adherant->setNumeroAdherent("AD-999999");
$adherant->setEmail("Sacripant@gmail.com");
$adherant->setNom("Sauvage");
$adherant->setPrenom("Sacripant");
$adherant->setDateAdhesion(new DateTime());

$entityManager->persist($adherant);
$entityManager->flush();