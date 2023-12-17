# Rendre disponible un nouveau média

## User story



- En tant que bibliothécaire
- Je veux rendre disponible un nouveau média
- Afin de le rendre empruntable par les adhérents de la bibliothèque

## Indications

- L’accès au média à rendre disponible se fait via son id.

## Critères d’acceptation

- Le média que l’on souhaite rendre disponible doit exister
- Seul un média ayant le statut “Nouveau” peut-être rendu disponible.

## Méthode implémentée
### execute($id) : bool

- Description : Rend disponible le média identifié par son id qui est `$id`

## Exemple de code

```php
// Configuration de la connexion à la base de données
// Utilisation d'une base de données SQLite en mémoire
$connection = DriverManager::getConnection(['driver' => 'pdo_sqlite','path' => ':memory:'], $config);

// Création des dépendences
$entityManager = new EntityManager($connection, $config);
$listerMedia = new ListerNouveauMedia($entityManager);

// Création du schema de la base de données
$schemaTool = new SchemaTool($entityManager);
$metadata = $entityManager->getMetadataFactory()->getAllMetadata();
$schemaTool->createSchema($metadata);

// Récupération des nouveaux médias
$rendreUnMediaDisponible = new RendreUnMediaDisponible($this -> entityManager);

// Création d'un livre dans la base de données
$creerLivre = new CreerLivre(
    $this -> entityManager,
    $this -> validator
);

$requete = new CreerLivreRequete(
    "Maniaque puissance",
    "987-2-9764-1092-0",
    "johndoe",
    50
);

$creerLivre -> execute($requete);

// Recupération du nouveau livre
$mediaSelectionne = $this -> entityManager -> getRepository(Media::class) -> findOneBy(["id" => 1]);

// Mise à jour du média
$rendreUnMediaDisponible -> execute(1);
```