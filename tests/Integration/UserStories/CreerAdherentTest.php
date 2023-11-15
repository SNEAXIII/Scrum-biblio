<?php

namespace App\Tests\Integration\UserStories;

use App\Entity\Adherent;
use App\Services\GeneratorNumeroAdherent;
use App\UserStories\CreerAdherent\{CreerAdherent, CreerAdherentRequete};
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, ORMSetup, Tools\SchemaTool};
use PHPUnit\Framework\{Attributes\Test, TestCase};
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerAdherentTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;
    protected GeneratorNumeroAdherent $generator;

    #[test]
    public function creerAdherent_ValeursCorrectes_True()
    {
        // Arrange
        $requete = new CreerAdherentRequete(
            "john",
            "doe",
            "johndoe@gmail.com");
        $creerAdherent = new CreerAdherent(
            $this->entityManager,
            $this->generator,
            $this->validator
        );
        // Act
        $resultat = $creerAdherent->execute($requete);
        // Assert
        $repository = $this->entityManager->getRepository(Adherent::class);
        $criteria = ["email" => "johndoe@gmail.com"];
        $adherent = $repository->findOneBy($criteria);
        $this->assertNotNull($adherent);
        $this->assertEquals("john", $adherent->getPrenom());
        $this->assertEquals("doe", $adherent->getNom());
    }

    #[test]
    public function creerAdherent_EmailMalSaisi_Exception()
    {
        // Arrange
        $requete = new CreerAdherentRequete(
            "john",
            "doe",
            "johndoe.com");
        $creerAdherent = new CreerAdherent(
            $this->entityManager,
            $this->generator,
            $this->validator
        );
        // Act
        $this->expectExceptionMessage("L'email n'est pas valide");
        $resultat = $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_DoublonEmail_Excetion()
    {
        // Arrange
        $mockGeneratorNumeroAdherent = $this->createMock(GeneratorNumeroAdherent::class);
        $mockGeneratorNumeroAdherent->method("execute")->willReturn("AD-999999");
        $requete = new CreerAdherentRequete(
            "john",
            "doe",
            "johndoe@gmail.com");
        $requete2 = new CreerAdherentRequete(
            "john",
            "doe",
            "johnydoe@gmail.com");
        $creerAdherent = new CreerAdherent(
            $this->entityManager,
            $mockGeneratorNumeroAdherent,
            $this->validator
        );
        // Act
        $resultat = $creerAdherent->execute($requete);
        $this->expectExceptionMessage("Le numéro d'adhérent existe déja dans la base de données");
        $resultat = $creerAdherent->execute($requete2);
    }

    #[test]
    public function creerAdherent_DoublonNumeroAdherent_Excetion()
    {
        // Arrange
        $requete = new CreerAdherentRequete(
            "john",
            "doe",
            "johndoe@gmail.com");
        $creerAdherent = new CreerAdherent(
            $this->entityManager,
            $this->generator,
            $this->validator
        );
        // Act
        $resultat = $creerAdherent->execute($requete);
        $this->expectExceptionMessage("L'email existe déja dans la base de données");
        $resultat = $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_EmailNonSaisi_Exeption()
    {
        // Arrange
        $requete = new CreerAdherentRequete(
            "john",
            "doe",
            "");
        $creerAdherent = new CreerAdherent(
            $this->entityManager,
            $this->generator,
            $this->validator
        );
        // Act
        $this->expectExceptionMessage("L'email doit etre saisi");
        $resultat = $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_PrenomNonSaisi_Exeption()
    {
        // Arrange
        $requete = new CreerAdherentRequete(
            "",
            "doe",
            "johndoe@gmail.com");
        $creerAdherent = new CreerAdherent(
            $this->entityManager,
            $this->generator,
            $this->validator
        );
        // Act
        $this->expectExceptionMessage("prenom");
        $resultat = $creerAdherent->execute($requete);
    }

    #[test]
    public function creerAdherent_NomNonSaisi_Exeption()
    {
        // Arrange
        $requete = new CreerAdherentRequete(
            "john",
            "",
            "johndoe@gmail.com");
        $creerAdherent = new CreerAdherent(
            $this->entityManager,
            $this->generator,
            $this->validator
        );
        // Act
        $this->expectExceptionMessage("nom");
        $resultat = $creerAdherent->execute($requete);
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
        $this->generator = new GeneratorNumeroAdherent();
        $this->validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
}