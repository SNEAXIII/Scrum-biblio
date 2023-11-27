<?php
namespace root;

require_once "./vendor/autoload.php";
/* @var $entityManager */
require_once "./bootstrap.php";

use App\Form\LivreForm;
use App\Form\MagazineForm;
use App\Form\MediaForm;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use Symfony\Component\Console\Style\SymfonyStyle as Style;
use Silly\Application;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ValidatorBuilder;

//Definir les commandes
$app = new Application();

function getValidator():ValidatorInterface
{
    return (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();
}

function getMediaForm(MediaForm $mediaForm, Style $io): MediaForm
{

    $titre = $io->ask("Veuillez saisir un titre ");
    $dateCreation = $io->ask("Veuillez saisir une date de creation ");

    $mediaForm->setTitre($titre);
    $mediaForm->setDateCreation($dateCreation);

    return $mediaForm;
}

function getAdherentForm(Style $io)
{

}

function getLivreForm(Style $io): LivreForm
{
    $livreForm = new LivreForm();

    $io->title("Outil de création d'un livre dans la BDD");

    $livreForm = getMediaForm($livreForm, $io);

    $livreForm->setIsbn(
        $io->ask("Veuillez saisir un ISBN ")
    );
    $livreForm->setAuteur(
        $io->ask("Veuillez saisir un auteur ")
    );
    $livreForm->setNombrePages(
        $io->ask("Veuillez saisir un nombre de page pour le livre ")
    );

    return $livreForm;
}

//function getMagazineForm(Style $io): MagazineForm
//{
//
//}

$app->command(
    'biblio:add [entity]',
    function ($entity, Style $io)
    use ($entityManager) {
        switch ($entity) {
            case "Adherent":
                getAdherentForm($io);
                break;

            case "Livre":
                $livreForm = getLivreForm($io);
                $requete = new CreerLivreRequete(
                    $livreForm->getTitre(),
                    $livreForm->getIsbn(),
                    $livreForm->getAuteur(),
                    $livreForm->getDateCreation(),
                    $livreForm->getNombrePages(),

                );
                $creerLivre = new CreerLivre(
                $entityManager,
                    getValidator()
                );
                $result = $creerLivre->execute($requete);
                break;

//            case "Magazine":
//                getMagazineForm($io);
//                break;

            default :
                $io->error("Vous n'avez pas saisi une entité valide");
                break;
        }
        $io->success("GG");
    });

$app->run();

