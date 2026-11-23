# Rapports de fin d'études - INSTI Lokossa

Application Laravel 8 : dépôt, validation et consultation des documents de licence, master et doctorat.

## Organisation

| Dossier | Contenu |
|---|---|
| `app/Http/Controllers` | Dépôt, profil, pages publiques ; `Admin/` pour l'administration |
| `app/Models` | `Tfe`, `Document`, `Niveau`, `Filiere`, `TfeModification`, `Journal`, `User` |
| `app/Http/Requests`, `app/Rules` | Règles de validation |
| `resources/views` | Vues ; `components/` pour les champs réutilisables |
| `public/css/m3.css`, `public/js/m3.js` | Style Material 3 et script, sans étape de compilation |
| `database/sql` | Scripts à lancer à la main dans phpMyAdmin |
| `storage/app` | Documents et justificatifs, jamais publics ni versionnés |

## Installation

    composer install
    cp .env.example .env
    php artisan key:generate

Utiliser une base locale : ne jamais lancer `migrate` ou `db:create` avec le `.env` de production.

## Tests

    php vendor/bin/phpunit --filter TfeAccessTest

Ils utilisent une base SQLite en mémoire.

## Production

`APP_ENV=production`, `APP_DEBUG=false`, `LOG_LEVEL=warning`, `APP_URL` en https, SMTP réel.
Après chaque envoi : vider `storage/framework/views` et supprimer `bootstrap/cache/routes-v7.php` s'il existe.

## Emails

Envoi par SMTP, puis par la messagerie de l'hébergeur si le SMTP est injoignable (`MAIL_MAILER=failover`).
Bouton « Tester l'envoi d'emails » dans Administration > Journal. `EMAIL_CONFIRMATION=false` lève l'obligation de confirmer l'adresse avant un dépôt.
