<?php
// Zentrale Konfiguration
define('SITE_NAME', 'LSW Cars');
define('SITE_TAGLINE', 'Ihr Partner für Premium-Fahrzeuge');
define('BASE_URL', '/lsw-cars');
define('UPLOAD_PATH', __DIR__ . '/../uploads');
define('UPLOAD_URL', BASE_URL . '/uploads');

// MySQL-Zugang (XAMPP Standard)
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'lsw_cars');
define('DB_CHARSET', 'utf8mb4');

// Kontaktdaten – hier anpassen
define('COMPANY_NAME', 'LSW Cars');
define('COMPANY_OWNER', 'Inhaber LSW Cars');
define('COMPANY_STREET', 'Petersbergstr. 101');
define('COMPANY_ZIP', '36100');
define('COMPANY_CITY', 'Petersberg');
define('COMPANY_PHONE', '015567 233437');
define('COMPANY_EMAIL', 'lsw_cars@outlook.de');
define('COMPANY_VAT', 'DE000000000');
define('COMPANY_HRB', '–');
define('COMPANY_COURT', '–');

// Admin-Zugang (Passwort beim ersten Login ändern!)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS_HASH', password_hash('admin123', PASSWORD_BCRYPT));

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
