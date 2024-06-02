<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/Css/index.css">
    <title>Ajouter un nouvel adhérent</title>
</head>
<body>

<?php

require_once "./bootstrap.php";
use App\Services\GeneratorNumeroAdherent;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use Symfony\Component\Validator\ValidatorBuilder;

$afficheFormulaire = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Déclaration des dépendances
    $generator = new GeneratorNumeroAdherent();
    $validator = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();
    $creerAdherent = new CreerAdherent($entityManager,$generator,$validator);
    $requete = new CreerAdherentRequete($_POST["prenom"],$_POST["nom"],$_POST["email"]);

    // Execution de la requête
    try {
        $executed=$creerAdherent->execute($requete);
        if (!$executed) {
            throw new Exception("La création d'un nouvel adhérant n'a pas pu se faire!");
        }
        $afficheFormulaire = false;
        echo "Adhérent ajouté avec succès !";
    }
    catch (Exception $e) {
        $message = $e->getMessage();
    }
}
If ($afficheFormulaire){
    ?>
    <h2>Ajouter un nouvel adhérent</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <?php if (isset($message)) echo "<label class=\"error\">$message</label>" ?>

        <input type="submit" value="Ajouter">
    </form>
    <?php
}
?>

</body>
</html>
