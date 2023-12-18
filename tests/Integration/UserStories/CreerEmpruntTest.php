<?php

namespace App\Tests\Integration\UserStories;

use App\Entity\Adherent;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Media;
use App\Services\DureeEmprunt;
use App\Services\GeneratorNumeroAdherent;
use App\Services\GeneratorNumeroEmprunt;
use App\Services\StatusMedia;
use App\Services\ValidatorNumeroEmprunt;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use App\UserStories\EmprunterUnMedia\CreerEmpruntRequete;
use App\UserStories\EmprunterUnMedia\EmprunterUnMedia;
use App\UserStories\RendreUnMediaDisponible\RendreUnMediaDisponible;
use App\UserStories\CreerLivre\{CreerLivre, CreerLivreRequete};
use DateTime;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\{EntityManager, EntityManagerInterface, ORMSetup, Tools\SchemaTool};
use PHPUnit\Framework\{Attributes\Test, TestCase};
use Exception;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

class CreerEmpruntTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;
    protected ValidatorNumeroEmprunt $validatorNumeroEmprunt;
    protected GeneratorNumeroEmprunt $generatorNumeroEmprunt;

    /**
     * @throws Exception
     */
    private function createLivreDansBDD()
    {
        $requete = new CreerLivreRequete(
            "La déchéance humaine",
            "987-2-9764-1092-0",
            "johndoe",
            50);
        $creerLivre = new CreerLivre(
            $this -> entityManager,
            $this -> validator
        );
        $creerLivre -> execute($requete);
    }

    /**
     * @throws Exception
     */
    private function createAdherentDansBDD()
    {
        $requete = new CreerAdherentRequete(
            "john",
            "doe",
            "johndoe@gmail.com");
        $creerAdherent = new CreerAdherent(
            $this -> entityManager,
            new GeneratorNumeroAdherent(),
            $this -> validator
        );
        $creerAdherent -> execute($requete);
    }

    private function rendDispo($id)
    {
        (new RendreUnMediaDisponible($this -> entityManager)) -> execute($id);
    }

    #[test]
    public function creerEmprunt_ValeursCorrectes_True()
    {
        $this -> createLivreDansBDD();
        $this -> rendDispo(1);
        $this -> createAdherentDansBDD();
        $adherentRepository = $this -> entityManager -> getRepository(Adherent::class);
        /** @var Adherent $adherent */
        $adherent = $adherentRepository -> find(1);
        $numeroAdherent = $adherent -> getNumeroAdherent();

        $emprunterUnMedia = new EmprunterUnMedia(
            $this -> entityManager,
            $this -> validator,
            $this -> validatorNumeroEmprunt,
            $this -> generatorNumeroEmprunt
        );
        $empruntRequete = new CreerEmpruntRequete(
            $numeroAdherent,
            1
        );
        $result = $emprunterUnMedia -> execute($empruntRequete);

        /** @var Emprunt $emprunt */
        $emprunt = $this -> entityManager -> getRepository(Emprunt::class) -> find(1);

        $this -> assertTrue($result);

        $this -> assertEquals(1, $emprunt -> getId());
        $this -> assertEquals($numeroAdherent, $emprunt -> getAdherent() -> getNumeroAdherent());
        $this -> assertEquals(1, $emprunt -> getMedia() -> getId());
        $this -> assertEquals(StatusMedia::EMPRUNTE, $emprunt -> getMedia() -> getStatus());
        $expectDateEstimee = (new DateTime())->modify("+".DureeEmprunt::LIVRE."days")->format("d/m/Y");
        $actualDateEstimee = $emprunt->getDateRetourEstime()->format("d/m/Y");
        $this -> assertEquals($expectDateEstimee, $actualDateEstimee);
        $this -> assertNull($emprunt->getDateRetourEffectif());
    }

    #[test]
    public function creerEmprunt_MediaNonDisponible_Exception()
    {
        $this -> createLivreDansBDD();
        $this -> createAdherentDansBDD();
        $adherentRepository = $this -> entityManager -> getRepository(Adherent::class);
        /** @var Adherent $adherent */
        $adherent = $adherentRepository -> find(1);
        $numeroAdherent = $adherent -> getNumeroAdherent();

        $emprunterUnMedia = new EmprunterUnMedia(
            $this -> entityManager,
            $this -> validator,
            $this -> validatorNumeroEmprunt,
            $this -> generatorNumeroEmprunt
        );
        $empruntRequete = new CreerEmpruntRequete(
            $numeroAdherent,
            1
        );
        $this -> expectExceptionMessage("Le média sélectionné n'est pas disponible");
        $result = $emprunterUnMedia -> execute($empruntRequete);
    }

    #[test]
    public function creerEmprunt_MediaInexistant_Exception()
    {
        $this -> createAdherentDansBDD();
        $adherentRepository = $this -> entityManager -> getRepository(Adherent::class);
        /** @var Adherent $adherent */
        $adherent = $adherentRepository -> find(1);
        $numeroAdherent = $adherent -> getNumeroAdherent();

        $emprunterUnMedia = new EmprunterUnMedia(
            $this -> entityManager,
            $this -> validator,
            $this -> validatorNumeroEmprunt,
            $this -> generatorNumeroEmprunt
        );
        $empruntRequete = new CreerEmpruntRequete(
            $numeroAdherent,
            1
        );
        $this -> expectExceptionMessage("Le média sélectionné n'existe pas dans la base de données");
        $result = $emprunterUnMedia -> execute($empruntRequete);
    }

    #[test]
    public function creerEmprunt_AdherentInexistant_Exception()
    {
        $this -> createLivreDansBDD();
        $this -> rendDispo(1);

        $emprunterUnMedia = new EmprunterUnMedia(
            $this -> entityManager,
            $this -> validator,
            $this -> validatorNumeroEmprunt,
            $this -> generatorNumeroEmprunt
        );
        $empruntRequete = new CreerEmpruntRequete(
            "AD-0", // numéro bidon
            1
        );
        $this -> expectExceptionMessage("L'adhérent sélectionné n'existe pas dans la base de données");
        $result = $emprunterUnMedia -> execute($empruntRequete);
    }

    #[test]
    public function creerEmprunt_AdherentNonRenouvele_Exception()
    {
        $this -> createLivreDansBDD();
        $this -> rendDispo(1);
        $this -> createAdherentDansBDD();
        $adherentRepository = $this -> entityManager -> getRepository(Adherent::class);
        /** @var Adherent $adherent */
        $adherent = $adherentRepository -> find(1);
        $numeroAdherent = $adherent -> getNumeroAdherent();

        $dateAdhesion = clone $adherent -> getDateAdhesion();
        $adherent -> setDateAdhesion($dateAdhesion -> modify("-2year"));

        $this -> entityManager -> flush();

        $emprunterUnMedia = new EmprunterUnMedia(
            $this -> entityManager,
            $this -> validator,
            $this -> validatorNumeroEmprunt,
            $this -> generatorNumeroEmprunt
        );
        $empruntRequete = new CreerEmpruntRequete(
            $numeroAdherent,
            1
        );
        $this -> expectExceptionMessage("L'adhésion n'est pas renouvelée");
        $result = $emprunterUnMedia -> execute($empruntRequete);

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
        $this -> validatorNumeroEmprunt = new ValidatorNumeroEmprunt();
        $this -> generatorNumeroEmprunt = new GeneratorNumeroEmprunt($this -> entityManager);
        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this -> entityManager);
        $metadata = $this -> entityManager -> getMetadataFactory() -> getAllMetadata();
        $schemaTool -> createSchema($metadata);
    }
}