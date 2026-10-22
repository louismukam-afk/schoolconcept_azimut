# Codes et catégories des programmes

Cette migration enrichit les matières existantes de `classe_matiere` à partir des
onze documents fournis le 22 octobre 2026. Le catalogue JSON contient uniquement
les intitulés, codes, semestres et catégories : aucun étudiant ni résultat.

## Périmètre

| Cycle | Spécialité | Niveau |
| --- | --- | --- |
| HND | Accountancy | I et II |
| BTS | Comptabilité et gestion des entreprises | I et II |
| BTS | Bâtiment | II |
| HND | Marketing Trade Sale | I |
| HND | Networks and Security | I |
| HND | Nursing | I |
| HND | Human Resource Management | I |
| HND | Banking and Finance | II |

Les spécialités sont identifiées par leur code **et** leur nom, puis par cycle,
niveau et semestre. Les copies de campus précédées de `-` sont reconnues.
Les intitulés français/anglais, accents, espaces et encodages anciens sont
comparés sans modifier les noms en base. Les numéros I/II restent distincts.
Les semestres globaux 3/4 correspondent aux semestres 1/2 du deuxième niveau.
Les programmes ne sont pas attachés à une année académique dans cette table :
les dates des noms de fichiers ne servent donc pas de filtre d'année.

## Catégories et codes

- Documents Word : fondamentales = `0`, transversales/Common Courses = `1`,
  professionnelles = `2`, d'après les rubriques explicites.
- Excel : les catégories existantes sont conservées, conformément au choix fait.
  Les en-têtes de modules servent uniquement à lire le document ; aucun module
  ou regroupement de calcul n'est créé.
- Un code vide est complété. Un ancien code de module à trois chiffres peut
  être développé en son code matière exact à quatre chiffres (ex. `CGE 111`
  devient `CGE 1111` pour la matière correspondante).
- Un autre code déjà renseigné, ou une catégorie non fondamentale explicitement
  incompatible, est conservé et signalé.
- Aucun crédit, coefficient, semestre, note ou rattachement n'est modifié.

Les correspondances sont exactes après normalisation. Il n'y a pas de recherche
approximative ni d'attribution par simple ressemblance de noms.

## Simulation et application

Après une sauvegarde de la base, depuis le dossier contenant `artisan` :

```bash
php scripts/preview-programme-references.php
```

Cette commande ne modifie pas la base. Elle écrit le détail dans
`storage/logs/programme_references_20261022.json` avec les anciens codes/types,
les valeurs proposées et les cas exclus. Le nombre de lignes dépend de la base.

Pour appliquer uniquement cette migration avec Laravel 5.0 :

```bash
php artisan migrate --path=database/migrations/programme_references --force
```

Le dossier dédié évite d'exécuter les autres migrations anciennes en attente.
Après import, la simulation doit afficher zéro ligne `update`.
Les relevés HND sauvegardés récupèrent ces métadonnées à leur réimpression.

## Cas à vérifier

`source_conflict` : les fichiers donnent plusieurs codes à un même intitulé,
ou un même code à plusieurs matières du même semestre. Exemples :
`GENERAL MATHEMATICS I` dans MTS ; `HUR 1133` et `PRINCIPLE OF MANAGEMENT` entre
les deux documents HRM. Ils ne sont pas corrigés automatiquement.

`ambiguous_programme` : plusieurs lignes du programme correspondent au même
code matière dans un même contexte. `unmatched_subject` : aucun intitulé exact.
`missing_source_code` : le code est absent ou mal positionné dans le document
(ex. CASE STUDY au semestre 2 du modèle ACC II).
Les autres conflits conservent les valeurs de la base.

## Sauvegarde et annulation

Chaque modification conserve ses anciennes et nouvelles valeurs dans
`programme_reference_backup_20261022`. La méthode `down()` restaure les valeurs
exactes. Elle s'arrête si un code ou type a été changé manuellement depuis
l'import, afin de ne pas écraser ce travail. Ne lancez pas un rollback global
sans vérifier le dernier lot de migrations : il pourrait contenir d'autres
opérations.
