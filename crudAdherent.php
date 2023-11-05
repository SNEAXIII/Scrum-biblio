<?php

use App\Entity\Adherent;

require "bootstrap.php";
require "vendor\autoload.php";

class CrudAdherant
{
    private $entityManager;


    public function __construct($entityManager)
    {
        $this -> entityManager = $entityManager;
    }


    public function addOne(string $numeroAdherent, string $email, string $nom, string $prenom)
    {
        $adherant = new Adherent();
        $adherant -> setNumeroAdherent("AD-999999");
        $adherant -> setEmail("Sacripant@carabistouille.vroom");
        $adherant -> setNom("Sauvage");
        $adherant -> setPrenom("Sacripant");
        $adherant -> setDateAdhesion(new DateTime());
        $this -> entityManager -> persist($adherant);
        $this -> entityManager -> flush();

    }

    public function delOne($idAdherent)
    {
        $adherent = $this -> entityManager -> getRepository(Adherent::class) -> find($idAdherent);

        if ($adherent) {
            $this -> entityManager -> remove($adherent);
            $this -> entityManager -> flush();
        } else {
            echo "L'adhérent n'a pas été trouvé.";
        }
    }

    public function getAll()
    {
        return $this -> entityManager -> getRepository(Adherent::class) -> findAll();
    }

}

$crudAdherant = new CrudAdherant($entityManager);

//$crudAdherant->addOne(
//    "AD-999999",
//    "Sacripant@carabistouille.vroom",
//    "Sauvage",
//    "Sacripant"
//);

//$crudAdherant->delOne(8);

dump($crudAdherant->getAll());