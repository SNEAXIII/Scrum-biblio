<?php

namespace App\Tests\Integration\UserStories;

use App\Entity\Livre;
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
            "iiiiiiiii",
            "johndoe",
            50,
            "05/07/1984");
        $creerLivre = new CreerLivre(
            $this->entityManager,
            $this->validator
        );
        // Act
        $resultat = $creerLivre->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Livre::class);
        $criteria = ["isbn" => "iiiiiiiii"];
        /** @var Livre $livre */
        $livre = $repository->findOneBy($criteria);
        $this->assertNotNull($livre);
        $this->assertEquals("La déchéance humaine", $livre->getTitre());
        $this->assertEquals("johndoe", $livre->getAuteur());
        $this->assertEquals(50, $livre->getNombrePages());
        $this->assertEquals("05/07/1984", $livre->getDateCreation());
        $this->assertEquals("Nouveau", $livre->getStatus());
        $this->assertTrue($resultat);
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
        $this->validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
}