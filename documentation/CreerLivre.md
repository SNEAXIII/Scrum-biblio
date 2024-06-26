# User story - Créer un livre

```
En tant que bibliothécaire
Je veux créer un livre
Afin de le rendre accessible aux adhérents de la bibliothèque
```
# Critères d’acceptation

```
Le titre, l’ISBN, l’auteur, le nombre de pages et la date de parution doivent être renseignés.
L’ISBN doit être unique.
```

# Classe Livre

Pour créer un nouveau livre dans la base donnée, il faut créer un objet de la classe `CreerLivreRequete` avec les
attributs spécifiés plus tard.

## Classe CreerLivreRequete

Cette classe représente les propriétés d'un livre :

### `$titre`

- Type : `string`
- Description : Le titre du livre.
- Contraintes de validation : Le titre ne doit pas être vide.

### `$isbn`

- Type : `string`
- Description : L'ISBN du livre.
- Contraintes de validation : L'ISBN ne doit pas être vide.

### `$auteur`

- Type : `string`
- Description : Le nom de l'auteur du livre.
- Contraintes de validation : Le nom de l'auteur ne doit pas être vide.

### `$nombrePages`

- Type : `int`
- Description : Le nombre de pages du livre.
- Contraintes de validation :
    - Le nombre de pages ne doit pas être vide.
    - Le nombre de pages doit être supérieur à 0.

## Classe CreerLivre

Cette classe permet de créer un livre dans la base de donnée, elle utilise les paramètres suivants :

### $entityManager

- Type : `EntityManagerInterface`
- Description : Correspond à l'entity manager paramétré pour se générer une base virtuelle.

### $validator

- Type : `ValidatorInterface`
- Description : Contient un validateur de Symfony avec les annotations actives

### execute(`$requete`) : bool

- Type `$requete` : `CreerLivreRequete`
- Description : Essaye d'exécuter la requête afin d'ajouter un livre dans la base de donnée, retourne `true` si
  l'enregistrement à bien été enregistré dans la base de donnée, sinon renvoie une exception.

## Exemple de code

```php
// Configuration de la connexion à la base de données
// Utilisation d'une base de données SQLite en mémoire
$connection = DriverManager::getConnection(['driver' => 'pdo_sqlite','path' => ':memory:'], $config);

// Création des dépendences
$entityManager = new EntityManager($connection, $config);
$validator = (new ValidatorBuilder())->enableAttributeMapping()->getValidator();

// Création du schema de la base de données
$schemaTool = new SchemaTool($entityManager);
$metadata = $entityManager->getMetadataFactory()->getAllMetadata();
$schemaTool->createSchema($metadata);

$requete = new CreerLivreRequete("La déchéance humaine","iiiiiiiii","johndoe",50);

$creerLivre = new CreerLivre($entityManager,$validator);

// Enregistrer dans la BDD
$resultat = $creerLivre->execute($requete);
```