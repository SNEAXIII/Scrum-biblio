<?php

namespace App\Tests\Integration\UserStories;


use App\Entity\Adherent;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Media;
use App\Services\DureeEmprunt;
use App\Services\NormaliseMedia;
use App\Services\StatusMedia;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\ListerNouveauMedia\ListerNouveauMedia;
use App\UserStories\RendreUnMediaDisponible\RendreUnMediaDisponible;
use App\UserStories\RetournerUnEmprunt\CreerRetourRequete;
use App\UserStories\RetournerUnEmprunt\RetournerUnEmprunt;
use App\UserStories\CreerAdherent\{CreerAdherent, CreerAdherentRequete};
use DateTime;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, ORMSetup, Tools\SchemaTool};
use PHPUnit\Framework\{Attributes\Test, TestCase};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class RetournerUnEmpruntTest extends TestCase
{
    protected EntityManagerInterface $entityManager;

    private function createAdherent()
    {
        $adherant = new Adherent();
        $adherant -> setNumeroAdherent("AD-999999");
        $adherant -> setEmail("Sauvage@gmail.com");
        $adherant -> setNom("Sauvage");
        $adherant -> setPrenom("Celine");
        $adherant -> setDateAdhesion(new DateTime());

        $this -> entityManager -> persist($adherant);
        $this -> entityManager -> flush();
    }

    private function createLivre()
    {
        $titre = "test";
        $isbn = "978-2070368228";
        $auteur = "johndoe";
        $nombrePage = 50;
        $livre = new Livre();
        $livre -> setTitre($titre);
        $livre -> setIsbn($isbn);
        $livre -> setAuteur($auteur);
        $livre -> setNombrePages($nombrePage);
        $livre -> setDateCreation(new \DateTime());
        $livre -> setStatus(StatusMedia::NOUVEAU);
        $livre -> setDureeEmprunt(DureeEmprunt::LIVRE);
        // Enregistrer l'adhérent en base de données
        $this -> entityManager -> persist($livre);
        $this -> entityManager -> flush($livre);
    }

    private function createEmprunt()
    {
        $numeroEmprunt = "EM-000000001";
        $intDureeEmprunt = DureeEmprunt::LIVRE;
        $dateActuelle = new DateTime();
        $dateEstimee = (new DateTime()) -> modify("+$intDureeEmprunt days");
        $mediaSelectionne = $this -> entityManager -> getRepository(Media::class) -> findOneBy(["id" => 1]);

        $emprunt = new Emprunt();
        $emprunt -> setDateRetourEffectif(null);
        $emprunt -> setDateEmprunt($dateActuelle);
        $emprunt -> setDateRetourEstime($dateEstimee);
        $emprunt -> setAdherent($this -> entityManager -> getRepository(Adherent::class) -> findOneBy(["id" => 1]));
        $emprunt -> setMedia($mediaSelectionne);
        $emprunt -> setNumeroEmprunt($numeroEmprunt);

        $this -> entityManager -> persist($emprunt);
        $mediaSelectionne -> setStatus(StatusMedia::EMPRUNTE);
        $this -> entityManager -> flush();
    }

    #[test]
    public function RetournerUnEmprunt_DonneesBienSaisies_True()
    {
        $this -> createAdherent();
        $this -> createLivre();
        $this -> createEmprunt();

        $numeroAdherent = "AD-999999";
        $numeroEmprunt = "EM-000000001";

        $retournerUnEmprunt = new RetournerUnEmprunt(
            $this -> entityManager,
            $this -> validator
        );
        $retournerUnEmpruntRequete = new CreerRetourRequete(
            $numeroEmprunt
        );

        $result = $retournerUnEmprunt -> execute($retournerUnEmpruntRequete);
        $this -> assertTrue($result);

        /** @var Emprunt $emprunt */
        $emprunt = $this -> entityManager -> getRepository(Emprunt::class) -> find(1);

        $this -> assertTrue($result);

        $this -> assertEquals(1, $emprunt -> getId());
        $this -> assertEquals($numeroAdherent, $emprunt -> getAdherent() -> getNumeroAdherent());
        $this -> assertEquals(1, $emprunt -> getMedia() -> getId());
        $this -> assertEquals(StatusMedia::DISPONIBLE, $emprunt -> getMedia() -> getStatus());
        $expectDateEstimee = (new DateTime())->modify("+".DureeEmprunt::LIVRE."days")->format("d/m/Y");
        $actualDateEstimee = $emprunt->getDateRetourEstime()->format("d/m/Y");
        $this -> assertEquals($expectDateEstimee, $actualDateEstimee);
        $this -> assertEquals((new DateTime())->format("d/m/Y"),$emprunt->getDateRetourEffectif()->format("d/m/Y"));
    }

    #[test]
    public function RetournerUnEmprunt_EmpruntDejaRetourne_Exception()
    {
        $this -> createAdherent();
        $this -> createLivre();
        $this -> createEmprunt();

        $numeroAdherent = "AD-999999";
        $numeroEmprunt = "EM-000000001";
        $idMedia = 1;

        $retournerUnEmprunt = new RetournerUnEmprunt(
            $this -> entityManager,
            $this -> validator
        );
        $retournerUnEmpruntRequete = new CreerRetourRequete(
            $numeroEmprunt
        );

        $result = $retournerUnEmprunt -> execute($retournerUnEmpruntRequete);
        $this -> assertTrue($result);
        $this -> expectExceptionMessage("Le retour a déjà été effectué");
        $result = $retournerUnEmprunt -> execute($retournerUnEmpruntRequete);
    }
    #[test]
    public function RetournerUnEmprunt_EmpruntInexistant_Exception()
    {
        $this -> createAdherent();
        $this -> createLivre();
        $this -> createEmprunt();

        $numeroAdherent = "AD-999999";
        $numeroEmprunt = "EM-100000001";
        $idMedia = 1;

        $retournerUnEmprunt = new RetournerUnEmprunt(
            $this -> entityManager,
            $this -> validator
        );
        $retournerUnEmpruntRequete = new CreerRetourRequete(
            $numeroEmprunt
        );

        $this -> expectExceptionMessage("L'emprunt sélectionné n'existe pas");
        $result = $retournerUnEmprunt -> execute($retournerUnEmpruntRequete);
    }
        #[test]
    public function RetournerUnEmprunt_NumeroEmpruntMauvaisFormat_Exception()
    {
        $this -> createAdherent();
        $this -> createLivre();
        $this -> createEmprunt();

        $numeroAdherent = "AD-999999";
        $numeroEmprunt = "EM-1001";
        $idMedia = 1;

        $retournerUnEmprunt = new RetournerUnEmprunt(
            $this -> entityManager,
            $this -> validator
        );
        $retournerUnEmpruntRequete = new CreerRetourRequete(
            $numeroEmprunt
        );
        $this -> expectExceptionMessage("Le numéro d'emprunt doit être correctement écrit (EM-XXXXXXXXX)");
        $result = $retournerUnEmprunt -> execute($retournerUnEmpruntRequete);
    }
        #[test]
    public function RetournerUnEmprunt_NumeroEmpruntNonRenseigne_Exception()
    {
        $this -> createAdherent();
        $this -> createLivre();
        $this -> createEmprunt();

        $numeroAdherent = "AD-999999";
        $numeroEmprunt = "";
        $idMedia = 1;

        $retournerUnEmprunt = new RetournerUnEmprunt(
            $this -> entityManager,
            $this -> validator
        );
        $retournerUnEmpruntRequete = new CreerRetourRequete(
            $numeroEmprunt
        );
        $this -> expectExceptionMessage("Le numéro d'emprunt doit être renseigné.");
        $result = $retournerUnEmprunt -> execute($retournerUnEmpruntRequete);
    }

    protected function setUp(): void
    {
        echo "setup ---------------------------------------------------------";
        // Configuration de Doctrine pour les tests
        $config = ORMSetup ::createAttributeMetadataConfiguration(
            [__DIR__ . '/../../../src/'],
            true
        );

        // Configuration de la connexion à la base de données
        // Utilisation d'une base de données SQLite en mémoire
        $connection = DriverManager ::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ], $config);
        // Création des dépendences
        $this -> entityManager = new EntityManager($connection, $config);
        $this -> validator = (new ValidatorBuilder()) -> enableAttributeMapping() -> getValidator();
        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this -> entityManager);
        $metadata = $this -> entityManager -> getMetadataFactory() -> getAllMetadata();
        $schemaTool -> createSchema($metadata);
    }
}