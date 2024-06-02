<?php

namespace App\Tests\Integration\UserStories;


use App\Entity\Media;
use App\Services\NormaliseMedia;
use App\Services\StatusMedia;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\ListerNouveauMedia\ListerNouveauMedia;
use App\UserStories\RendreUnMediaDisponible\RendreUnMediaDisponible;
use App\UserStories\CreerAdherent\{CreerAdherent, CreerAdherentRequete};
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, ORMSetup, Tools\SchemaTool};
use PHPUnit\Framework\{Attributes\Test, TestCase};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class RendreUnMediaDisponibleTest extends TestCase
{
    protected EntityManagerInterface $entityManager;

    #[test]
    public function RendreUnMediaDisponible_MediaCorrectementModifie_True()
    {
        // Arrange
        $rendreUnMediaDisponible = new RendreUnMediaDisponible($this -> entityManager);
        $creerLivre = new CreerLivre(
            $this -> entityManager,
            $this -> validator
        );
        $requete1 = new CreerLivreRequete(
            "Maniaque puissance",
            "987-2-9764-1092-0",
            "johndoe",
            50
        );

        // Act
        $creerLivre -> execute($requete1);

        /**
         * @var $mediaSelectionne Media
         */
        $mediaSelectionne = $this -> entityManager -> getRepository(Media::class) -> findOneBy(["id" => 1]);

        $this -> assertEquals(StatusMedia::NOUVEAU, $mediaSelectionne -> getStatus());

        $result = $rendreUnMediaDisponible -> execute(1);
        // Assert
        $this -> assertEquals(StatusMedia::DISPONIBLE, $mediaSelectionne -> getStatus());
        $this -> assertTrue($result);
    }

    #[test]
    public function RendreUnMediaDisponible_MediaInexistant_Exception()
    {
        // Arrange
        $rendreUnMediaDisponible = new RendreUnMediaDisponible($this -> entityManager);
        // Act
        $this -> expectExceptionMessage("Le média sélectionné n'existe pas dans la base de données");
        $result = $rendreUnMediaDisponible -> execute(1);
    }

    #[test]
    public function RendreUnMediaDisponible_MediaPasNouveau_Exception()
    {
        // Arrange
        $rendreUnMediaDisponible = new RendreUnMediaDisponible($this -> entityManager);
        $creerLivre = new CreerLivre(
            $this -> entityManager,
            $this -> validator
        );
        $requete1 = new CreerLivreRequete(
            "Maniaque puissance",
            "987-2-9764-1092-0",
            "johndoe",
            50
        );

        // Act
        $creerLivre -> execute($requete1);

        /**
         * @var $mediaSelectionne Media
         */
        $mediaSelectionne = $this -> entityManager -> getRepository(Media::class) -> findOneBy(["id" => 1]);

        $this -> assertEquals(StatusMedia::NOUVEAU, $mediaSelectionne -> getStatus());

        $result = $rendreUnMediaDisponible -> execute(1);

        $this -> assertEquals(StatusMedia::DISPONIBLE, $mediaSelectionne -> getStatus());

        $this -> expectExceptionMessage("n'a pas le statut \"Nouveau\"");
        $result = $rendreUnMediaDisponible -> execute(1);

        $this -> assertTrue($result);
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