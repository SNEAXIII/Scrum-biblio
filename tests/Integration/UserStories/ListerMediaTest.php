<?php

namespace App\Tests\Integration\UserStories;


use App\Services\NormaliseMedia;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
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
    public function ListerMedia_OrdonneParDateDecroissante_Array()
    {
        // Arrange
        $titre1 = "La déchéance humaine1";
        $titre2 = "La déchéance humaine2";
        $isbn1 = "987-2-9764-1092-0";
        $isbn2 = "978-2070368228";
        $auteur = "johndoe";
        $nombrePage = 50;
        $listerMedia = new ListerNouveauMedia($this -> entityManager);
        $creerLivre = new CreerLivre(
            $this -> entityManager,
            $this -> validator
        );
        $requete1 = new CreerLivreRequete(
            $titre1,
            $isbn1,
            $auteur,
            $nombrePage
        );
        $requete2 = new CreerLivreRequete(
            $titre2,
            $isbn2,
            $auteur,
            $nombrePage
        );
        // Act
        $creerLivre -> execute($requete1);
        sleep(2);
        $creerLivre -> execute($requete2);
        /**
         * @var array $result [NormaliseMedia]
         */
        $result = $listerMedia -> execute();
        // Assert
        $this -> assertNotEmpty($result);
        $this -> assertIsArray($result);
        $isOrdByDesc = $result[0] -> getDateCreation() > $result[1] -> getDateCreation();
        $this -> assertTrue($isOrdByDesc);
    }

    #[test]
    public function ListerMedia_OrdonneParDateCroissante_Array()
    {
        // Arrange
        $titre1 = "La déchéance humaine1";
        $titre2 = "La déchéance humaine2";
        $isbn1 = "987-2-9764-1092-0";
        $isbn2 = "978-2070368228";
        $auteur = "johndoe";
        $nombrePage = 50;
        $listerMedia = new ListerNouveauMedia($this -> entityManager);
        $creerLivre = new CreerLivre(
            $this -> entityManager,
            $this -> validator
        );
        $requete1 = new CreerLivreRequete(
            $titre1,
            $isbn1,
            $auteur,
            $nombrePage
        );
        $requete2 = new CreerLivreRequete(
            $titre2,
            $isbn2,
            $auteur,
            $nombrePage
        );
        // Act
        $creerLivre -> execute($requete1);
        sleep(2);
        $creerLivre -> execute($requete2);
        /**
         * @var array $result [NormaliseMedia]
         */
        $result = $listerMedia -> execute();
        // Assert
        $this -> assertNotEmpty($result);
        $this -> assertIsArray($result);
        $isNotOrdByDesc = $result[0] -> getDateCreation() < $result[1] -> getDateCreation();
        $this -> assertFalse($isNotOrdByDesc);
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
        $this -> validator = (new ValidatorBuilder()) -> enableAnnotationMapping() -> getValidator();
        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this -> entityManager);
        $metadata = $this -> entityManager -> getMetadataFactory() -> getAllMetadata();
        $schemaTool -> createSchema($metadata);
    }
}