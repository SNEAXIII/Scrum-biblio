<?php

namespace App\Tests\Integration\UserStories;


use App\UserStories\ListerNouveauMedia\ListerNouveauMedia;
use App\UserStories\CreerAdherent\{CreerAdherent, CreerAdherentRequete};
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, ORMSetup, Tools\SchemaTool};
use PHPUnit\Framework\{Attributes\Test, TestCase};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class ListerMediaTest extends TestCase
{
    protected EntityManagerInterface $entityManager;

    #[test]
    public function creerAdherent_ValeursCorrectes_True()
    {
        // Arrange
        $listerMedia = new ListerNouveauMedia($this->entityManager);
        // Act
        $result = $listerMedia->execute();
        dump($result);
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