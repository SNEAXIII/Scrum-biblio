# Emprunter un média

## User story
- En tant que bibliothécaire
- Je veux enregistrer un emprunt de média disponible pour un adhérent
- Afin de permettre à l'adhérent d'utiliser ce média pour pour durée
déterminée

## Indications

- L’accès au média à rendre disponible se fait via son id.

## Critères d’acceptation
### Média

Le média doit exister dans la base de données

Le média doit être disponible

### Adhérent

L’adhérent doit exister dans la base de données

L’adhésion de l’adhérent doit être valide

### Enregistrement dans la base de données

L’emprunt doit être correctement enregistré dans la base de données. La date de retour prévue doit être correctement initialisée en fonction du média emprunté (livre, bluray ou magazine) ainsi que la date d’emprunt.

A l’issue de l’enregistrement de l’emprunt, le statut du média doit être à “Emprunte”.

### Messages d’erreurs

Des messages d’erreur explicites doivent être retournés en cas d’informations manquantes ou incorrectes.

## Méthode implémentée

### execute($requete) : bool

- Description : utilise une requete de la classe CreerEmpruntRequete

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
$emprunterUnMedia = new EmprunterUnMedia(
      $entityManager,
      new ValidatorBuilder()) -> enableAttributeMapping() -> getValidator(),
      new ValidatorNumeroEmprunt(),
      new GeneratorNumeroEmprunt($entityManager)
);
            $empruntRequete = new CreerEmpruntRequete(
                $numero_adherent,
                $id_media
            );
            $emprunterUnMedia -> execute($empruntRequete);
```