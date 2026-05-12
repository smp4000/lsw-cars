<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/logo.php';
$page = $page ?? '';
$title = $title ?? SITE_NAME;
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="description" content="<?= e(SITE_NAME) ?> – <?= e(SITE_TAGLINE) ?>. Premium-Fahrzeuge zu fairen Preisen.">
<title><?= e($title) ?> · <?= e(SITE_NAME) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/theme.css">
</head>
<body data-page="<?= e($page) ?>">

<header class="site-header" id="siteHeader">
  <div class="container header-inner">
    <a class="logo" href="<?= BASE_URL ?>/" aria-label="LSW Cars Startseite">
      <?= lsw_logo_svg('compact') ?>
    </a>

    <nav class="nav-main" aria-label="Hauptnavigation">
      <a href="<?= BASE_URL ?>/" class="<?= $page==='home'?'active':'' ?>">Start</a>
      <a href="<?= BASE_URL ?>/fahrzeuge.php" class="<?= $page==='fahrzeuge'?'active':'' ?>">Fahrzeuge</a>
      <a href="<?= BASE_URL ?>/leistungen.php" class="<?= $page==='leistungen'?'active':'' ?>">Leistungen</a>
      <a href="<?= BASE_URL ?>/ueber-uns.php" class="<?= $page==='ueber'?'active':'' ?>">Über uns</a>
      <a href="<?= BASE_URL ?>/kontakt.php" class="<?= $page==='kontakt'?'active':'' ?>">Kontakt</a>
    </nav>

    <a href="<?= BASE_URL ?>/kontakt.php" class="btn btn-primary btn-cta">Termin vereinbaren</a>

    <button class="nav-toggle" id="navToggle" aria-label="Menü" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<main class="site-main">
