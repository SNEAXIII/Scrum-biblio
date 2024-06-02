<?php

namespace App\Tests\Integration\UserStories;

use App\Entity\Magazine;
use App\Services\StatusMedia;
use App\UserStories\CreerMagazine\CreerMagazine;
use App\UserStories\CreerMagazine\CreerMagazineRequete;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, ORMSetup, Tools\SchemaTool};
use PHPUnit\Framework\{Attributes\Test, TestCase};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerMagazineTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;

    #[test]
    public function creerMagazine_ValeursCorrectes_True()
    {
        // Arrange
        $requete = new CreerMagazineRequete(
            "La déchéance humaine",
            "15 bis",
            "01/07/1984"
        );
        $creerMagazine = new CreerMagazine(
            $this->entityManager,
            $this->validator
        );
        // Act
        $resultat = $creerMagazine->execute($requete);
        $repository = $this->entityManager->getRepository(Magazine::class);
        $criteria = ["titre" => "La déchéance humaine", "numero" => "15 bis"];
        /** @var Magazine $magazine */
        $magazine = $repository->findOneBy($criteria);
        // Assert
        $this->assertNotNull($magazine);
        $this->assertEquals("La déchéance humaine", $magazine->getTitre());
        $this->assertEquals("15 bis", $magazine->getNumero());
        $this->assertEquals("01/07/1984", $magazine->getDatePublication());
        $this->assertEquals((new \DateTime())->format("d/m/Y"), $magazine->getDateCreation()->format("d/m/Y"));
        $this->assertEquals(StatusMedia::NOUVEAU, $magazine->getStatus());
        $this->assertTrue($resultat);
    }

    #[test]
    public function creerMagazine_TitreNonRenseigne_Exception()
    {
        $requete = new CreerMagazineRequete(
            "",
            "15 bis",
            "01/07/1984"
        );
        $creerMagazine = new CreerMagazine(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("titre");
        $creerMagazine->execute($requete);
    }

    #[test]
    public function creerMagazine_NumeroNonRenseigne_Exception()
    {
        $requete = new CreerMagazineRequete(
            "La déchéance humaine",
            "",
            "01/07/1984"
        );
        $creerMagazine = new CreerMagazine(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("numéro");
        $creerMagazine->execute($requete);
    }

    #[test]
    public function creerMagazine_DatePublicationNonRenseigne_Exception()
    {
        $requete = new CreerMagazineRequete(
            "La déchéance humaine",
            "15 bis",
            ""
        );
        $creerMagazine = new CreerMagazine(
            $this->entityManager,
            $this->validator
        );
        $this->expectExceptionMessage("publication");
        $creerMagazine->execute($requete);
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