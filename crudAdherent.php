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
        $adherant -> setNumeroAdherent($numeroAdherent);
        $adherant -> setEmail($email);
        $adherant -> setNom($nom);
        $adherant -> setPrenom($prenom);
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
    public function getOne($id)
    {
        return $this -> entityManager -> getRepository(Adherent::class) ->findOneBy(["id"=>$id ]);
    }

}

$crudAdherant = new CrudAdherant($entityManager);

$crudAdherant->addOne(
    "AD-999999",
    "johndoe@gmail.com@gmail.com",
    "Sauvage",
    "Celine"
);

dump($crudAdherant->getOne(40));