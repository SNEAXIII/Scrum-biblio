<?php

namespace App\Tests\Integration\UserStories;

use App\Entity\Livre;
use App\Services\StatusMedia;
use App\UserStories\CreerLivre\{CreerLivre, CreerLivreRequete};
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, ORMSetup, Tools\SchemaTool};
use PHPUnit\Framework\{Attributes\Test, TestCase};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerLivreTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;

    #[test]
    public function creerLivre_ValeursCorrectes_True()
    {
        // Arrange
        $requete = new CreerLivreRequete(
            "La déchéance humaine",
            "987-2-9764-1092-0",
            "johndoe",

            50);
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        // Act
        $resultat = $creerLivre->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Livre::class);
        $criteria = ["isbn" => "987-2-9764-1092-0"];
        /** @var Livre $livre */
        $livre = $repository->findOneBy($criteria);
        $this->assertNotNull($livre);
        $this->assertEquals("987-2-9764-1092-0", $livre->getIsbn());
        $this->assertEquals("La déchéance humaine", $livre->getTitre());
        $this->assertEquals("johndoe", $livre->getAuteur());
        $this->assertEquals(50, $livre->getNombrePages());
        $this->assertEquals((new \DateTime())->format("d/m/Y"), $livre->getDateCreation()->format("d/m/Y"));
        $this->assertEquals(StatusMedia::NOUVEAU, $livre->getStatus());
        $this->assertTrue($resultat);
    }

    #[test]
    public function creerLivre_TitreNonRenseigne_Exception()
    {
        $requete = new CreerLivreRequete(
            "",
            "987-2-9764-1092-0",
            "johndoe",
            50);
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("titre");
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_ISBNNonRenseigne_Exception()
    {
        $requete = new CreerLivreRequete(
            "La déchéance humaine",
            "",
            "johndoe",
            50
        );
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("L'ISBN doit être renseigné.");
        $creerLivre->execute($requete);
    }
    #[test]
    public function creerLivre_ISBNMalRenseigne_Exception()
    {
        $requete = new CreerLivreRequete(
            "La déchéance humaine",
            "iiiiiii",
            "johndoe",
            50
        );
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("L'ISBN doit avoir un format valide.");
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_ISBNNonUnique_Exception()
    {
        $requete = new CreerLivreRequete(
            "La déchéance humaine",
            "987-2-9764-1092-0",
            "johndoe",
            50
        );
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        $creerLivre->execute($requete);
        $this->expectExceptionMessage("isbn existe");
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_AuteurNonRenseigne_Exception()
    {
        $requete = new CreerLivreRequete(
            "La déchéance humaine",
            "987-2-9764-1092-0",
            "",
            50
        );
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("auteur");
        $creerLivre->execute($requete);
    }

    #[test]
    public function creerLivre_NBPagesNonRenseigne_Exception()
    {
        $requete = new CreerLivreRequete(
            "La déchéance humaine",
            "987-2-9764-1092-0",
            "johndoe",
            null
        );
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("pages doit être renseigné");
        $creerLivre->execute($requete);
    }
    #[test]
    public function creerLivre_NBPagesMalRenseigne_Exception()
    {
        $requete = new CreerLivreRequete(
            "La déchéance humaine",
            "987-2-9764-1092-0",
            "johndoe",
            -10
        );
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("de pages doit être valide");
        $creerLivre->execute($requete);
    }

    protected function setUp(): void
    {
        echo "setup ---------------------------------------------------------";
        // Configuration de Doctrine pour les tests
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__ . '/../../../src/'],
            true
        );

        // Configuration de la connexion à la base de données
        // Utilisation d'une base de données SQLite en mémoire
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ], $config);

        // Création des dépendences
        $this->entityManager = new EntityManager($connection, $config);
        $this->validator = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();
        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
}