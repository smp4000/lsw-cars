<?php
/**
 * Zentrale Konfiguration.
 *
 * Produktiv-Zugangsdaten werden NICHT hier gepflegt, sondern in einer
 * separaten Datei `includes/config.local.php`. Diese überschreibt die
 * Defaults unten und ist via .gitignore vom Repository ausgeschlossen.
 *
 * Vorlage: includes/config.local.example.php
 */

// 1) Lokale/Produktive Konfiguration zuerst laden, falls vorhanden
$__local = __DIR__ . '/config.local.php';
if (is_file($__local)) {
    require_once $__local;
}

// 2) Defaults (XAMPP) – greifen nur, wenn nicht via config.local.php gesetzt

defined('SITE_NAME')    or define('SITE_NAME',    'LSW Cars');
defined('SITE_TAGLINE') or define('SITE_TAGLINE', 'Ihr Partner für Premium-Fahrzeuge');

// BASE_URL automatisch aus Projektpfad + DocumentRoot ableiten
if (!defined('BASE_URL')) {
    $proj = realpath(__DIR__ . '/..');
    $doc  = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
    if ($proj && $doc && strpos($proj, $doc) === 0) {
        $rel = substr($proj, strlen($doc));
        $rel = str_replace(DIRECTORY_SEPARATOR, '/', $rel);
        $rel = '/' . ltrim($rel, '/');
        $rel = rtrim($rel, '/');
        define('BASE_URL', $rel === '' ? '' : $rel);
    } else {
        define('BASE_URL', '');
    }
}

defined('UPLOAD_PATH') or define('UPLOAD_PATH', __DIR__ . '/../uploads');
defined('UPLOAD_URL')  or define('UPLOAD_URL',  BASE_URL . '/uploads');

// MySQL-Zugang (Default = XAMPP)
defined('DB_HOST')    or define('DB_HOST',    '127.0.0.1');
defined('DB_USER')    or define('DB_USER',    'root');
defined('DB_PASS')    or define('DB_PASS',    '');
defined('DB_NAME')    or define('DB_NAME',    'lsw_cars');
defined('DB_CHARSET') or define('DB_CHARSET', 'utf8mb4');

// Firmendaten
defined('COMPANY_NAME')   or define('COMPANY_NAME',   'LSW Cars');
defined('COMPANY_OWNER')  or define('COMPANY_OWNER',  'Inhaber LSW Cars');
defined('COMPANY_STREET') or define('COMPANY_STREET', 'Petersbergstr. 101');
defined('COMPANY_ZIP')    or define('COMPANY_ZIP',    '36100');
defined('COMPANY_CITY')   or define('COMPANY_CITY',   'Petersberg');
defined('COMPANY_PHONE')  or define('COMPANY_PHONE',  '015567 233437');
defined('COMPANY_EMAIL')  or define('COMPANY_EMAIL',  'lsw_cars@outlook.de');
defined('COMPANY_VAT')    or define('COMPANY_VAT',    'DE000000000');
defined('COMPANY_HRB')    or define('COMPANY_HRB',    '–');
defined('COMPANY_COURT')  or define('COMPANY_COURT',  '–');

// Admin-Zugang
defined('ADMIN_USER')      or define('ADMIN_USER',      'admin');
defined('ADMIN_PASS_HASH') or define('ADMIN_PASS_HASH', password_hash('admin123', PASSWORD_BCRYPT));

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Europe/Berlin');
mb_internal_encoding('UTF-8');

function e(string $v): string {
    return htmlspecialchars($v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function fmt_price($v): string {
    return number_format((float)$v, 0, ',', '.') . ' €';
}

function fmt_km($v): string {
    return number_format((float)$v, 0, ',', '.') . ' km';
}
