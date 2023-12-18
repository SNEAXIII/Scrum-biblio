<?php

namespace root;

require_once "./vendor/autoload.php";
/* @var $entityManager */
require_once "./bootstrap.php";

use App\Form\AdherentForm;
use App\Form\LivreForm;
use App\Form\MagazineForm;
use App\Form\MediaForm;
use App\Services\GeneratorNumeroAdherent;
use App\Services\GeneratorNumeroEmprunt;
use App\Services\StatusMedia;
use App\Services\ValidatorNumeroEmprunt;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\UserStories\EmprunterUnMedia\CreerEmpruntRequete;
use App\UserStories\EmprunterUnMedia\CreerMagazine;
use App\UserStories\EmprunterUnMedia\CreerMagazineRequete;
use App\UserStories\EmprunterUnMedia\EmprunterUnMedia;
use App\UserStories\ListerNouveauMedia\ListerNouveauMedia;
use App\UserStories\RendreUnMediaDisponible\RendreUnMediaDisponible;
use mysql_xdevapi\Exception;
use Silly\Application;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Style\SymfonyStyle as Style;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

//Definir les commandes
$app = new Application();

function printEnd(mixed $reussi, string $entity, Style $io): void
{
    if ($reussi) {
        $io -> success("L'entité $entity a bien été ajoutée à la base de donnée");
    } else {
        $io -> error("L'entité $entity n'a pas été ajoutée à la base de donnée");
    }
}

function getValidator(): ValidatorInterface
{
    return (new ValidatorBuilder()) -> enableAnnotationMapping() -> getValidator();
}

function getMediaForm(MediaForm $mediaForm, Style $io): MediaForm
{

    $titre = $io -> ask("Veuillez saisir un titre ");

    $mediaForm -> setTitre($titre);

    return $mediaForm;
}

function getAdherentForm(Style $io): AdherentForm
{
    $adherentForm = new AdherentForm();

    $io -> title("Outil de création d'un adhérent dans la BDD");

    $adherentForm -> setNom(
        $io -> ask("Veuillez saisir un nom ")
    );
    $adherentForm -> setPrenom(
        $io -> ask("Veuillez saisir un prenom ")
    );
    $adherentForm -> setEmail(
        $io -> ask("Veuillez saisir un email ")
    );

    return $adherentForm;
}

function getLivreForm(Style $io): MediaForm
{
    $livreForm = new LivreForm();

    $io -> title("Outil de création d'un livre dans la BDD");

    $livreForm = getMediaForm($livreForm, $io);

    $livreForm -> setIsbn(
        $io -> ask("Veuillez saisir un ISBN ")
    );
    $livreForm -> setAuteur(
        $io -> ask("Veuillez saisir un auteur ")
    );
    $livreForm -> setNombrePages(
        $io -> ask("Veuillez saisir un nombre de page pour le livre ")
    );

    return $livreForm;
}

function getMagazineForm(Style $io): MediaForm
{
    $magazineForm = new MagazineForm();

    $io -> title("Outil de création d'un magazine dans la BDD");

    $magazineForm = getMediaForm($magazineForm, $io);

    $magazineForm -> setNumero(
        $io -> ask("Veuillez saisir un numéro ")
    );
    $magazineForm -> setDatePublication(
        $io -> ask("Veuillez saisir une date de publication ")
    );

    return $magazineForm;
}

$app -> command(
    'biblio:add:Livre',
    function (Style $io)
    use ($entityManager) {
        $livreForm = getLivreForm($io);
        $requete = new CreerLivreRequete(
            $livreForm -> getTitre(),
            $livreForm -> getIsbn(),
            $livreForm -> getAuteur(),
            $livreForm -> getNombrePages(),
        );
        $creerLivre = new CreerLivre(
            $entityManager,
            getValidator()
        );
        $reussi = $creerLivre -> execute($requete);
        printEnd($reussi, "Livre", $io);
    },
    ["biblio:add:livre"]
);
$app -> command(
    'biblio:add:Adherent',
    function (Style $io)
    use ($entityManager) {
        $adherentForm = getAdherentForm($io);
        $requete = new CreerAdherentRequete(
            $adherentForm -> getNom(),
            $adherentForm -> getPrenom(),
            $adherentForm -> getEmail()
        );
        $creerAdherent = new CreerAdherent(
            $entityManager,
            new GeneratorNumeroAdherent(),
            getValidator()
        );
        $reussi = $creerAdherent -> execute($requete);
        printEnd($reussi, "Adherent", $io);
    },
    ["biblio:add:adherent"]
);
$app -> command(
    'biblio:add:Magazine',
    function (Style $io)
    use ($entityManager) {
        $magazineForm = getMagazineForm($io);
        $requete = new CreerMagazineRequete(
            $magazineForm -> getTitre(),
            $magazineForm -> getNumero(),
            $magazineForm -> getDatePublication(),
        );
        $creerMagazine = new CreerMagazine(
            $entityManager,
            getValidator()
        );
        $reussi = $creerMagazine -> execute($requete);
        printEnd($reussi, "Magazine", $io);
    },
    ["biblio:add:magazine"]
);
$app -> command(
    'biblio:get:all',
    function (Style $io) use ($entityManager) {
        $listerMedia = new ListerNouveauMedia($entityManager);
        $arrayNouveauMedia = $listerMedia -> execute();
        if (empty($arrayNouveauMedia)) {
            $io -> error("La base de donnée ne contient pas de nouveaux médias.");
            return;
        }
        // Affichage du tableau
        $table = new Table($io);
        $table -> setStyle('box-double');
        $table -> setHeaderTitle('Liste des nouveaux médias');
        $table -> setHeaders(['Id', 'Titre', 'Status', 'Date de creation', 'Type de média']);

        foreach ($arrayNouveauMedia as $media) {
            $table -> addRow([
                $media -> getId(),
                $media -> getTitre(),
                StatusMedia ::getStatusName($media -> getStatut()),
                $media -> getDateCreation() -> format("d/m/Y"),
                $media -> getTypeMedia()]);
        }
        $table -> render();
    }
);
$app -> command(
    'biblio:update:one [id]',
    function ($id, Style $io) use ($entityManager) {
        $rendreUnMediaDisponible = new RendreUnMediaDisponible($entityManager);
        try {
            $rendreUnMediaDisponible -> execute($id);
            $io -> success("Le média a bien été mis à l'état disponible dans la base de donnée");
        } catch (Exception $e) {
            $io -> error($e);
        }
    }
);
$app -> command(
    'biblio:add:emprunt',
    function (Style $io) use ($entityManager) {

        $numero_adherent = $io -> ask("Choisissez un numéro d'adherent");

        $id_media = intval($io -> ask("Choisissez un numéro de média"));

        try {
            $emprunterUnMedia = new EmprunterUnMedia(
                $entityManager,
                getValidator(),
                new ValidatorNumeroEmprunt(),
                new GeneratorNumeroEmprunt($entityManager)
            );
            $empruntRequete = new CreerEmpruntRequete(
                $numero_adherent,
                $id_media
            );
            $emprunterUnMedia -> execute($empruntRequete);
            $io -> success("L'emprunt a bien été effectué");
        } catch (Exception $e) {
            $io -> error($e);
        }
    }
);

$app -> run();

