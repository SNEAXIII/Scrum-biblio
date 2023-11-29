# User story - Créer un magazine

```
En tant que bibliothécaire
Je veux créer un livre
Afin de le rendre accessible aux adhérents de la bibliothèque
```
# Critères d’acceptation

```
Le titre, le numéro et la date de parution doivent être renseignés.
```


# Classe Magazine

Pour créer un nouveau magazine dans la base donnée, il faut créer un objet de la classe `CreerMagazineRequete` avec les
attributs spécifiés plus tard.

## Classe CreerMagazineRequete

Cette classe représente les propriétés d'un magazine :

### `$titre`

- Type : `string`
- Description : Le titre du magazine.
- Contraintes de validation : Le titre ne doit pas être vide.

### `numero`

- Type : `string`
- Description : Le numéro du magazine.
- Contraintes de validation : L'ISBN ne doit pas être vide.

### `$datePublication`

- Type : `string`
- Description : La date de publication du magazine.
- Contraintes de validation : La date de création ne doit pas être vide.

## Classe CreerMagazine

Cette classe permet de créer un magazine dans la base de donnée, elle utilise les paramètres suivants :

### $entityManager

- Type : `EntityManagerInterface`
- Description : Correspond à l'entity manager paramétré pour se générer une base virtuelle.

### $validator

- Type : `ValidatorInterface`
- Description : Contient un validateur de Symfony avec les annotations actives

### execute(`$requete`) : bool

- Type `$requete` : `CreerMagazineRequete`
- Description : Essaye d'exécuter la requête afin d'ajouter un magazine dans la base de donnée, retourne `true` si
  l'enregistrement à bien été enregistré dans la base de donnée, sinon renvoie une exception.

## Exemple de code

```php
// Configuration de la connexion à la base de données
// Utilisation d'une base de données SQLite en mémoire
$connection = DriverManager::getConnection(['driver' => 'pdo_sqlite','path' => ':memory:'], $config);

// Création des dépendences
$entityManager = new EntityManager($connection, $config);
$validator = (new ValidatorBuilder())->enableAnnotationMapping()->getValidator();

// Création du schema de la base de données
$schemaTool = new SchemaTool($entityManager);
$metadata = $entityManager->getMetadataFactory()->getAllMetadata();
$schemaTool->createSchema($metadata);

$requete = new CreerLivreRequete("La déchéance humaine","iiiiiiiii","johndoe",50);

$creerMagazine = new CreerMagazine($entityManager,$validator);

// Enregistrer dans la BDD
$resultat = $creerMagazine->execute($requete);
```