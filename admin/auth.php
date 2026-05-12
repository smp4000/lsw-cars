<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/logo.php';

function admin_require_login(): void {
    if (empty($_SESSION['admin'])) {
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

function admin_header(string $page, string $title): void {
    ?>
    <!DOCTYPE html>
    <html lang="de">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?= e($title) ?> · Admin · <?= e(SITE_NAME) ?></title>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
      <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/theme.css">
    </head>
    <body>
      <div class="admin-shell">
        <aside class="admin-sidebar">
          <a class="logo" href="<?= BASE_URL ?>/admin/" style="display:block;">
            <?= lsw_logo_svg('compact') ?>
            <div style="text-align:center;margin-top:.25rem;font-size:.7rem;letter-spacing:.3em;color:var(--c-gold-2);">ADMIN</div>
          </a>
          <nav class="admin-nav">
            <a href="<?= BASE_URL ?>/admin/index.php"            class="<?= $page==='dashboard'?'active':'' ?>">📊 Dashboard</a>
            <a href="<?= BASE_URL ?>/admin/fahrzeuge.php"         class="<?= $page==='vehicles'?'active':'' ?>">🚗 Fahrzeuge</a>
            <a href="<?= BASE_URL ?>/admin/fahrzeug-bearbeiten.php" class="<?= $page==='vehicle_new'?'active':'' ?>">➕ Neues Fahrzeug</a>
            <a href="<?= BASE_URL ?>/admin/nachrichten.php"       class="<?= $page==='messages'?'active':'' ?>">✉️ Nachrichten</a>
            <a href="<?= BASE_URL ?>/" target="_blank">↗ Webseite öffnen</a>
            <a href="<?= BASE_URL ?>/admin/logout.php" style="margin-top:1rem;color:#ff8a8a;">↩ Abmelden</a>
          </nav>
        </aside>
        <main class="admin-main">
    <?php
}

function admin_footer(): void {
    echo '</main></div></body></html>';
}
