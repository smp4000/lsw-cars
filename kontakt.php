<?php
require_once __DIR__ . '/includes/db.php';
$page = 'kontakt';
$title = 'Kontakt';

$msgOk = false; $msgErr = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $tel = trim($_POST['telefon'] ?? '');
    $nachricht = trim($_POST['nachricht'] ?? '');
    $datenschutz = isset($_POST['datenschutz']);

    if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || !$nachricht) {
        $msgErr = 'Bitte füllen Sie alle Pflichtfelder korrekt aus.';
    } elseif (!$datenschutz) {
        $msgErr = 'Bitte stimmen Sie der Datenschutzerklärung zu.';
    } else {
        $ins = db()->prepare('INSERT INTO contact_messages (name, email, telefon, nachricht) VALUES (?,?,?,?)');
        $ins->execute([$name, $email, $tel, $nachricht]);
        $msgOk = true;
        $_POST = [];
    }
}

include __DIR__ . '/includes/header.php';
?>

<header class="page-header">
  <div class="container">
    <h1>Kontakt</h1>
    <p>Wir freuen uns auf Ihre Nachricht – persönlich, telefonisch oder per E-Mail.</p>
  </div>
</header>

<div class="container section" style="padding-top:2rem;">
  <div style="display:grid;grid-template-columns:1.4fr 1fr;gap:3rem;">
    <div>
      <h2>Schreiben Sie uns</h2>

      <?php if ($msgOk): ?>
        <div class="alert alert-success">Vielen Dank! Ihre Nachricht wurde gesendet. Wir melden uns zeitnah.</div>
      <?php elseif ($msgErr): ?>
        <div class="alert alert-error"><?= e($msgErr) ?></div>
      <?php endif; ?>

      <form method="post" class="form-stack">
        <div class="form-row">
          <div><label for="name">Name *</label><input type="text" id="name" name="name" required value="<?= e($_POST['name'] ?? '') ?>"></div>
          <div><label for="email">E-Mail *</label><input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></div>
        </div>
        <div><label for="tel">Telefon</label><input type="tel" id="tel" name="telefon" value="<?= e($_POST['telefon'] ?? '') ?>"></div>
        <div><label for="msg">Nachricht *</label><textarea id="msg" name="nachricht" required><?= e($_POST['nachricht'] ?? '') ?></textarea></div>
        <label class="checkbox-row">
          <input type="checkbox" name="datenschutz" required>
          <span>Ich habe die <a href="<?= BASE_URL ?>/datenschutz.php" target="_blank">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner Daten zur Bearbeitung der Anfrage zu. *</span>
        </label>
        <button class="btn btn-primary btn-lg" type="submit">Nachricht senden</button>
      </form>
    </div>

    <aside style="background:var(--c-surface-2);border:1px solid var(--c-border);border-radius:var(--radius-lg);padding:2rem;height:fit-content;">
      <h3>So erreichen Sie uns</h3>
      <p style="color:var(--c-text);">
        <strong><?= e(COMPANY_NAME) ?></strong><br>
        <?= e(COMPANY_STREET) ?><br>
        <?= e(COMPANY_ZIP) ?> <?= e(COMPANY_CITY) ?>
      </p>
      <p style="color:var(--c-text);">
        📞 <a href="tel:<?= e(preg_replace('/\s+/', '', COMPANY_PHONE)) ?>"><?= e(COMPANY_PHONE) ?></a><br>
        ✉ <a href="mailto:<?= e(COMPANY_EMAIL) ?>"><?= e(COMPANY_EMAIL) ?></a>
      </p>
      <h4 style="margin-top:1.5rem;">Öffnungszeiten</h4>
      <p style="color:var(--c-text);">
        Mo–Fr: 9:00 – 18:00 Uhr<br>
        Sa: 10:00 – 14:00 Uhr<br>
        So: geschlossen
      </p>
    </aside>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
