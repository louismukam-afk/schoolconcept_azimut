# Deuxième lot : codes et catégories des programmes

Ce lot complète le premier import sans modifier son catalogue ni sa migration.
Les 60 fichiers fournis ont été lus (73 feuilles). Le catalogue étendu contient
48 profils de spécialité/niveau et 1 469 références de matières après fusion des
copies et prise en compte des références précédentes.

## Deux migrations distinctes

1. `2026_10_22_000002_import_extended_programme_codes` modifie uniquement
   `classe_matiere.id_code` et sauvegarde les valeurs dans
   `programme_reference_codes_v2`.
2. `2026_10_22_000003_classify_extended_programme_categories` modifie uniquement
   `classe_matiere.type` et sauvegarde les valeurs dans
   `programme_reference_types_v2`.

Les notes, crédits, coefficients, semestres, rattachements et groupes UE/modules
ne sont pas modifiés. Les fichiers Word/Excel originaux ne sont pas nécessaires
sur le serveur. Le catalogue ne contient ni noms d'étudiants ni notes.

## Classement approuvé pour ce nouveau lot

| Indication du module | Catégorie | Valeur `type` |
| --- | --- | --- |
| Dernier chiffre 1 ou 2 | Fondamentale | 0 |
| Dernier chiffre 3, 4, 5 ou 6 | Professionnelle | 2 |
| Dernier chiffre 7 | Transversale / Common Course | 1 |

Exemple : `NWS 1111`, rattaché au module `NWS 111`, est fondamental ;
`NWS 1131`, rattaché à `NWS 113`, est professionnel ; `NWS 1171` est transversal.
La règle s'applique uniquement si le code matière à quatre chiffres est bien
préfixé par le code du module présent dans le document. Il ne s'agit pas d'une
classification déduite du seul nom de matière.

Les rubriques explicites des anciens modèles Word restent prioritaires quand
elles corroborent la même matière et le même code. Les codes courts d'examens
nationaux (ex. `BA 10`, `BFI 17`) ne sont pas classés par leur dernier chiffre :
une rubrique explicite est utilisée si elle existe ; sinon la catégorie en base
est conservée et signalée `category_unclassified`.

Cette convention a été approuvée pour le deuxième lot. Le premier lot conserve
son choix initial de ne pas déduire de catégorie des fichiers Excel.

## Correspondances et exclusions

Les correspondances exigent la spécialité (code **et** nom), le cycle, le niveau,
le semestre et l'intitulé français/anglais normalisé. La troisième année et ses
semestres globaux 5/6 sont reconnus dans ce catalogue, sans créer de niveau.
Le profil `sin3` est conservé dans les références mais n'a actuellement aucun
contexte BTS niveau III dans la base locale : aucune autre classe ne le remplace.

Les copies identiques sont fusionnées ; leur provenance est conservée. Un code
de module à trois chiffres peut être précisé par le code matière à quatre
chiffres effectivement fourni pour le même intitulé. Aucun chiffre n'est inventé.

Les conflits entre sources, les doublons du programme et les valeurs existantes
incompatibles restent exclus. Une catégorie non nulle et différente de 0 n'est
pas écrasée si elle contredit la proposition. Le rapport donne les anciennes
valeurs, les valeurs proposées, la source et l'origine du classement.

Le fichier `echantillonmatierenew.xlsx` est un modèle fictif, exclu de l'import.
Les deux classeurs de résultats (15 feuilles au total) n'offrent pas de codes
matières fiables ; leurs notes et données d'étudiants ne sont pas importées.
Les libellés de certains en-têtes de résultats ne correspondent pas à la feuille,
ce qui interdit également de les utiliser pour attribuer automatiquement un code.

## Simulation et déploiement cPanel

Après sauvegarde de la base et récupération de la branche du projet :

```bash
git pull --ff-only origin modifications-azimut-20261021
php scripts/preview-programme-references.php --extended
```

Le rapport est écrit dans
`storage/logs/programme_references_extended_20261022.json`. La simulation présente
séparément les codes et les catégories, sans modifier la base.

Pour appliquer les migrations non encore exécutées de ce dossier uniquement :

```bash
php artisan migrate --path=database/migrations/programme_references --force
php artisan cache:clear
php scripts/preview-programme-references.php --extended
```

La migration du premier lot, si elle est déjà exécutée, n'est pas rejouée.
Le simple `php artisan migrate` ne parcourt pas ce sous-dossier avec Laravel 5.0.

Sur la base locale après le premier lot, la simulation trouve **1 671 codes** et
**1 054 catégories** à modifier (286 transversales et 768 professionnelles ; les
fondamentales correspondantes sont déjà à 0). Les nombres dépendent de la base.
Les 477 lignes `category_unclassified`, les 501 intitulés non appariés et les
autres conflits restent à vérifier dans le rapport, sans attribution hasardeuse.

## Vérifications et annulation

Les tests couvrent les trois catégories, les deux opérations indépendantes,
les conflits, les doublons, les niveaux et les semestres. Les migrations ont été
testées dans une transaction annulée sur la base locale : premier passage,
deuxième passage sans changement, refus d'écraser une modification ultérieure,
puis restauration exacte des lignes initiales.

Le relevé HND sauvegardé n° 7 a aussi été vérifié après application dans cette
transaction de test : semestre 1 = 5 fondamentales, 4 transversales et
6 professionnelles ; semestre 2 = 7 fondamentales, 2 transversales et
7 professionnelles. Les moyennes et le document sauvegardé restent inchangés.

Chaque méthode `down()` ne restaure que son propre champ et vérifie qu'il n'a pas
été modifié depuis l'import. Les migrations doivent normalement être annulées
dans l'ordre inverse : catégories, puis codes. Vérifier le dernier lot avant un
rollback global, qui peut inclure d'autres migrations.
