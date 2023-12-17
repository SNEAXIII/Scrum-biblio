# Lister les nouveaux médias
## User story
- En tant que bibliothécaire
- Je veux lister les nouveaux médias
- Afin de les rendre disponibles aux adhérents de la bibliothèque

## Critères d’acceptation
### Valeurs retournées
Un tableau contenant des objets média normalisés de la classe `NormaliseMedia` avec les informations suivantes :

- l’id du média
- le titre du média
- le statut du média
- la date de création du média (date à laquelle il a été créé dans la BD)
- le type du média (livre, bluray ou magazine)
### Ordre de tri

- La liste devra être triée par date de création décroissante.
# Classe ListerNouveauMedia

Pour récupérer les nouveaux médias, il faut d'abord créer un objet de la classe `ListerNouveauMedia`
Il lui faut un `EntityManagerInterface` pour se connecter à la BDD
## execute() : array


- Description : Récupère les nouveaux médias sous la forme `array[NormaliseMedia]`

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
$arrayNouveauMedia = $listerMedia->execute();
```