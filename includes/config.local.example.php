<?php
/**
 * Vorlage für umgebungsspezifische Konfiguration.
 *
 * Diese Datei nach `config.local.php` kopieren (im selben Ordner)
 * und mit den echten Zugangsdaten füllen. Die Datei wird per .gitignore
 * vom Repository ausgeschlossen, damit Passwörter nicht commited werden.
 *
 *   cp includes/config.local.example.php includes/config.local.php
 *
 * Sämtliche Konstanten sind optional – nur die wirklich abweichenden
 * Werte müssen gesetzt werden, alles andere bleibt bei den Defaults
 * aus config.php.
 */

// MySQL (z. B. All-Inkl)
define('DB_HOST', 'localhost');
define('DB_USER', 'd0000000');
define('DB_PASS', 'mein_db_passwort');
define('DB_NAME', 'd0000000');

// Admin-Zugang – mit eigenem Bcrypt-Hash überschreiben.
// Hash erzeugen z. B. via:
//   php -r "echo password_hash('MeinSicheresPasswort', PASSWORD_BCRYPT), PHP_EOL;"
// define('ADMIN_USER', 'admin');
// define('ADMIN_PASS_HASH', '$2y$10$....');

// Falls die Webseite NICHT im Unterverzeichnis läuft (z. B. Subdomain-Root),
// einfach auf leeren String setzen:
// define('BASE_URL', '');
