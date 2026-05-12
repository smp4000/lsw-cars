<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/logo.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? '');
    $pass = $_POST['pass'] ?? '';
    if ($user === ADMIN_USER && password_verify($pass, ADMIN_PASS_HASH)) {
        $_SESSION['admin'] = $user;
        header('Location: ' . BASE_URL . '/admin/');
        exit;
    }
    $error = 'Benutzername oder Passwort ist ungültig.';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login · Admin · <?= e(SITE_NAME) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/theme.css">
</head>
<body>
<div class="login-shell">
  <div class="login-card">
    <div class="logo" style="display:block;text-align:center;">
      <?= lsw_logo_svg('full') ?>
    </div>
    <h2 class="text-center" style="margin-bottom:.3em;">Anmelden</h2>
    <p class="text-center" style="margin-bottom:1.5rem;">Bitte melden Sie sich an, um den Admin-Bereich zu betreten.</p>

    <?php if ($error): ?>
      <div class="alert alert-error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-stack">
      <div><label for="u">Benutzer</label><input type="text" id="u" name="user" required autofocus></div>
      <div><label for="p">Passwort</label><input type="password" id="p" name="pass" required></div>
      <button class="btn btn-primary btn-block btn-lg" type="submit">Anmelden</button>
    </form>

    <p class="text-center mt-3" style="font-size:.85rem;color:var(--c-text-mute);">
      Standard: <code>admin</code> / <code>admin123</code><br>
      Bitte in <code>includes/config.php</code> ändern.
    </p>
    <p class="text-center"><a href="<?= BASE_URL ?>/" style="font-size:.9rem;">← zurück zur Webseite</a></p>
  </div>
</div>
</body>
</html>
