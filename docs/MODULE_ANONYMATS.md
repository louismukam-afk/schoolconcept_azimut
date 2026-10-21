# Module d’anonymat des examens — Azimut

Portage du module du projet local IPMBTPE, adapté à l’application Azimut. L’application source et sa base ne sont pas modifiées.

## Accès et fonctionnalités

Depuis l’accueil de gestion des notes, choisir **Anonymats des examens**, ou ouvrir `/eleves/notes/anonymats` sous la racine publique de l’application.

- Sélection de l’année active, du campus, cycle, niveau, filière, spécialité, semestre et de la tutelle d’épreuve.
- Génération par examen ou par matières cochées, pour la session normale et les rattrapages. Une nouvelle demande pour la même sélection conserve les codes déjà attribués.
- Liste de contrôle et de correspondance, fiches de report anonymes, fiches de présence, impression et export PDF global.
- Saisie des examens/rattrapages par code d’anonymat ; saisie nominative des CC et TPE configurés.
- Notes par modules/UV, moyennes, décisions et PV avec historique consultable.

Les routes utilisent l’authentification et les permissions de consultation ou de modification des notes existantes. Aucune affectation en tant qu’enseignant n’est exigée. Les verrouillages actifs des notes restent applicables.

Les notes saisies sur 20 sont stockées avec la pondération de la session d’examen, comme la saisie Soa. Un rattrapage sans pondération propre reprend celle de la session normale. Une session absente, une épreuve désactivée ou une sélection ambiguë est refusée. Les notes des autres sessions, campus ou programmes ne sont pas écrasées.

## Migrations et déploiement

Le module utilise les tables existantes du projet Azimut : inscriptions, programmes, sessions d’examen, campus, tutelles, regroupements de matières et PV.

Après sauvegarde de la base, depuis le dossier contenant `artisan`, exécuter uniquement le bloc du module :

```bash
php artisan migrate --path=database/migrations/anonymats --force
```

Laravel 5.0 ne recherche pas automatiquement ce sous-dossier avec un simple `migrate`. Le chemin explicite est nécessaire. Les migrations créent `anonymats_examens`, ajoutent `note.id_anonymat` et complètent `pv_generations` avec les champs d’historique HTML, contexte, campus et tutelle. Elles vérifient l’existence des tables/colonnes avant ajout. Aucun anonymat ni aucune note du projet source n’est importé.

Transférer le contrôleur et le modèle du module, `app/Http/routes_anonymats.php`, son inclusion dans `app/Http/routes.php`, le modèle `app/note.php`, les vues `resources/views/eleves/anonymats/`, les boutons des vues d’accueil/sélection des notes et le dossier de migrations. Conserver la configuration, les fichiers importés, les logos et les dépendances du serveur.

## PDF

Les fiches utilisent le format A4 portrait ; les PV de modules utilisent le format A3 paysage. Les en-têtes sont ceux d’Azimut et des campus/tutelles configurés. Les styles et les images locales lisibles sont incorporés dans les exports et copies historiques.

Un navigateur Chrome/Chromium/Edge installé sur le serveur permet le téléchargement direct. Son chemin peut être fourni via la variable d’environnement `ANONYMATS_BROWSER_PATH`.

Si le navigateur ou l’exécution de commandes n’est pas disponible sur l’hébergement cPanel, l’export affiche le document et ouvre l’impression du navigateur : choisir **Enregistrer au format PDF**. Pour utiliser systématiquement cette méthode, définir `ANONYMATS_PDF_MODE=browser` dans l’environnement du serveur.

Le dossier `storage/app/anonymats-pdf` doit être accessible en écriture pour la génération côté serveur. Les fichiers temporaires sont nettoyés après génération/envoi.

## Vérification locale

```bash
php tests/AnonymatModuleTest.php
```

Ce contrôle d’intégration utilise le contexte local de notes Soa (classe 366, cycle 2, niveau 10, année 2025-2026). Il teste les migrations présentes, routes, génération, absence de doublons, saisie et pondération, rattrapages, verrouillages, vues, exports par navigateur et historique des PV. Les écritures du test sont annulées par transaction. Il nécessite une base locale configurée avec ce contexte.
