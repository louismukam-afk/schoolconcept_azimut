# Déploiement de SchoolConcept Azimut

Cette version contient le code et les ressources du projet local, notamment les relevés BTS/HND, les procès-verbaux Soa et la saisie des notes par session d’examen.

## Configuration locale

- Installer les dépendances avec Composer dans un environnement compatible avec Laravel 5.0 et le code PHP historique du projet. Les dossiers vendor et vendorissba ne sont pas versionnés.
- Créer un fichier .env à partir de .env.example et renseigner les paramètres du déploiement.
- Fournir config/env.php par un canal sécurisé. config/env.example.php indique la structure attendue. Pour reprendre un déploiement existant, conserver sa clé d’origine : les paramètres de base de données du projet sont chiffrés avec cette clé.
- Créer les répertoires storage/framework/cache, storage/framework/sessions, storage/framework/views et storage/logs, avec les permissions nécessaires au serveur PHP.
- Restaurer les données de production et les fichiers importés par les outils de déploiement habituels ; aucune sauvegarde de base de données ni aucun fichier importé n’est inclus ici.
- Examiner et appliquer les migrations nécessaires sur une base sauvegardée. Ne pas lancer une réinitialisation des migrations sur la base existante.

## Vérifications ciblées

```text
php tests/BtsAnnualTranscriptTest.php
php tests/HndAnnualTranscriptTest.php
php tests/ExamSessionNoteTest.php
php tests/SoaPvSummaryTest.php
node tests/SoaPvPdfTest.js
```

Les essais PDF créent uniquement des fichiers anonymisés dans .codex_tmp, qui est ignoré par Git.

## Ressources locales non accessibles

Les fichiers suivants n’ont pas pu être copiés en raison des permissions Windows. Les restaurer depuis l’installation d’origine :

- public/img/campus/campus_20260901204340_6a972aec1dea1.png
- public/img/campus/campus_20260901211720_6a9732d0c4bb5.png
- public/img/tutelles/faculte_20260902111724_6a97f7b450a1e.jpg
- public/img/tutelles/faculte_20260902123712_6a980a683f73d.jpg
- public/img/tutelles/faculte_20260902124740_6a980cdc28b00.jpg
- public/img/tutelles/faculte_20260902124849_6a980d21aca37.jpeg
- public/img/tutelles/tutelle_20260902100544_6a97e6e88d5f8.jpeg
- public/img/tutelles/tutelle_20260902111724_6a97f7b45035b.png
- public/img/tutelles/tutelle_20260902123712_6a980a683f05a.jpg
- public/img/tutelles/tutelle_20260902124740_6a980cdc281a3.jpg
