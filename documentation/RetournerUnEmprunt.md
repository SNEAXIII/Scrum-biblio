# Emprunter un média

## User story
- En tant que bibliothécaire
- Je veux retourner un emprunt afin de rendre à nouveau le média emprunté disponible

## Indications

- L’accès a l'emprunt se fait via son numéro d'emprunt.

## Critères d’acceptation
### Emprunt

L'emprunt doit exister dans la BDD.
l'emprunt ne dois pas être déja retourné.
le média doit être mis à l'état disponible.
la date retour dois être mise à jour.

### Messages d’erreurs

Des messages d’erreur explicites doivent être retournés en cas d’informations manquantes ou incorrectes.

## Méthode implémentée

### execute($requete) : bool

- Description : utilise une requete de la classe CreerRetourRequete
## Test d'intégrations

[//]: # (todo finir)
## Exemple de code

```php
$retournerUnEmprunt = new RetournerUnEmprunt(
    entityManager,
    validator
);
$retournerUnEmpruntRequete = new CreerRetourRequete(
    $numeroEmprunt
);
$result = $retournerUnEmprunt -> execute($retournerUnEmpruntRequete);
```