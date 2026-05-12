<?php
require_once __DIR__ . '/includes/vehicle.php';

$id = (int)($_GET['id'] ?? 0);
$pdo = db();
$stmt = $pdo->prepare('SELECT * FROM vehicles WHERE id = ?');
$stmt->execute([$id]);
$v = $stmt->fetch();

if (!$v) {
    http_response_code(404);
    $page = 'fahrzeuge'; $title = 'Fahrzeug nicht gefunden';
    include __DIR__ . '/includes/header.php';
    echo '<div class="container section"><div class="empty-state"><h3>Fahrzeug nicht gefunden</h3><p>Das angefragte Fahrzeug ist nicht mehr verfügbar.</p><a href="' . BASE_URL . '/fahrzeuge.php" class="btn btn-primary mt-2">Zur Übersicht</a></div></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$images = vehicle_images($id);
$page  = 'fahrzeuge';
$title = $v['titel'];

// Kontaktformular
$msgOk = false; $msgErr = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'kontakt') {
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
        $ins = $pdo->prepare('INSERT INTO contact_messages (vehicle_id, name, email, telefon, nachricht) VALUES (?,?,?,?,?)');
        $ins->execute([$id, $name, $email, $tel, $nachricht]);
        $msgOk = true;
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="container section" style="padding-top:2rem;">
  <a href="<?= BASE_URL ?>/fahrzeuge.php" style="color:var(--c-text-mute);font-size:.9rem;">← Zurück zur Übersicht</a>

  <div class="detail-grid">
    <div>
      <div class="gallery">
        <div class="gallery-main">
          <?php if (count($images)): ?>
            <img src="<?= e($images[0]) ?>" alt="<?= e($v['titel']) ?>">
          <?php else: ?>
            <?= vehicle_placeholder_svg() ?>
          <?php endif; ?>
        </div>
        <?php if (count($images) > 1): ?>
          <div class="gallery-thumbs">
            <?php foreach ($images as $i => $src): ?>
              <button type="button" class="<?= $i===0?'active':'' ?>">
                <img src="<?= e($src) ?>" alt="">
              </button>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <h2 style="margin-top:2.5rem;">Beschreibung</h2>
      <p style="white-space:pre-line;color:var(--c-text);"><?= e($v['beschreibung'] ?: 'Keine Beschreibung vorhanden.') ?></p>

      <h2 style="margin-top:2.5rem;">Fahrzeugdaten</h2>
      <table class="specs-table">
        <tr><th>Marke / Modell</th><td><?= e($v['marke']) ?> <?= e($v['modell']) ?></td></tr>
        <tr><th>Erstzulassung</th><td><?= e(format_ez($v['erstzulassung'])) ?></td></tr>
        <tr><th>Kilometerstand</th><td><?= e(fmt_km($v['kilometerstand'])) ?></td></tr>
        <tr><th>Kraftstoff</th><td><?= e($v['kraftstoff'] ?: '–') ?></td></tr>
        <tr><th>Getriebe</th><td><?= e($v['getriebe'] ?: '–') ?></td></tr>
        <tr><th>Leistung</th><td><?= $v['leistung_kw']?e($v['leistung_kw']).' kW ':'' ?><?= $v['leistung_ps']?'('.e($v['leistung_ps']).' PS)':'–' ?></td></tr>
        <?php if ($v['hubraum']): ?><tr><th>Hubraum</th><td><?= e(number_format((float)$v['hubraum'],0,',','.')) ?> cm³</td></tr><?php endif; ?>
        <tr><th>Farbe</th><td><?= e($v['farbe'] ?: '–') ?></td></tr>
        <?php if ($v['karosserie']): ?><tr><th>Karosserie</th><td><?= e($v['karosserie']) ?></td></tr><?php endif; ?>
        <?php if ($v['tueren']): ?><tr><th>Türen</th><td><?= e($v['tueren']) ?></td></tr><?php endif; ?>
        <?php if ($v['sitze']):  ?><tr><th>Sitze</th><td><?= e($v['sitze']) ?></td></tr><?php endif; ?>
        <tr><th>Zustand</th><td><?= e($v['zustand']) ?></td></tr>
        <?php if ($v['hu']): ?><tr><th>HU</th><td><?= e($v['hu']) ?></td></tr><?php endif; ?>
        <?php if ($v['anzahl_halter']): ?><tr><th>Halter</th><td><?= e($v['anzahl_halter']) ?></td></tr><?php endif; ?>
      </table>

      <?php
      $equipment = [];
      foreach ([
        'klimaanlage' => 'Klimaanlage',
        'navigation' => 'Navigationssystem',
        'sitzheizung' => 'Sitzheizung',
        'einparkhilfe' => 'Einparkhilfe',
        'tempomat' => 'Tempomat',
        'anhaengerkupplung' => 'Anhängerkupplung',
        'ledersitze' => 'Ledersitze',
        'schiebedach' => 'Schiebedach',
      ] as $k => $label) {
          if (!empty($v[$k])) $equipment[] = $label;
      }
      ?>
      <?php if ($equipment): ?>
        <h2 style="margin-top:2.5rem;">Ausstattung</h2>
        <ul class="equip-grid" style="padding:0;">
          <?php foreach ($equipment as $eq): ?>
            <li><?= e($eq) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <aside class="detail-card">
      <span class="brand-tag"><?= e($v['marke']) ?></span>
      <h1><?= e($v['titel']) ?></h1>
      <div class="detail-price"><?= fmt_price($v['preis']) ?></div>
      <div class="detail-price-note">Preis inkl. MwSt., ausweisbar</div>

      <a href="tel:<?= e(preg_replace('/\s+/', '', COMPANY_PHONE)) ?>" class="btn btn-primary btn-block btn-lg">📞 <?= e(COMPANY_PHONE) ?></a>
      <a href="#kontaktForm" class="btn btn-dark btn-block">Nachricht senden</a>
      <a href="<?= BASE_URL ?>/kontakt.php" class="btn btn-ghost btn-block">Probefahrt vereinbaren</a>

      <hr style="border:none;border-top:1px solid var(--c-border);margin:1.5rem 0;">

      <h3 style="font-size:1rem;">Schnellüberblick</h3>
      <ul style="list-style:none;padding:0;margin:0;display:grid;gap:.6rem;font-size:.95rem;">
        <li>📅 Erstzulassung: <strong><?= e(format_ez($v['erstzulassung'])) ?></strong></li>
        <li>🛣️ Kilometerstand: <strong><?= e(fmt_km($v['kilometerstand'])) ?></strong></li>
        <li>⛽ Kraftstoff: <strong><?= e($v['kraftstoff'] ?: '–') ?></strong></li>
        <li>⚙️ Getriebe: <strong><?= e($v['getriebe'] ?: '–') ?></strong></li>
      </ul>
    </aside>
  </div>

  <section id="kontaktForm" class="section" style="padding:4rem 0 0;">
    <div style="max-width:780px;margin:0 auto;background:#fff;border:1px solid var(--c-border);border-radius:var(--radius-lg);padding:2.5rem;">
      <h2 style="margin-bottom:.5em;">Interesse an diesem Fahrzeug?</h2>
      <p style="margin-bottom:1.5rem;">Senden Sie uns eine Nachricht – wir melden uns schnellstmöglich bei Ihnen zurück.</p>

      <?php if ($msgOk): ?>
        <div class="alert alert-success">Vielen Dank! Ihre Nachricht wurde gesendet. Wir melden uns zeitnah.</div>
      <?php elseif ($msgErr): ?>
        <div class="alert alert-error"><?= e($msgErr) ?></div>
      <?php endif; ?>

      <form method="post" class="form-stack">
        <input type="hidden" name="action" value="kontakt">
        <div class="form-row">
          <div><label for="k_name">Name *</label><input type="text" id="k_name" name="name" required value="<?= e($_POST['name'] ?? '') ?>"></div>
          <div><label for="k_email">E-Mail *</label><input type="email" id="k_email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></div>
        </div>
        <div><label for="k_tel">Telefon</label><input type="tel" id="k_tel" name="telefon" value="<?= e($_POST['telefon'] ?? '') ?>"></div>
        <div><label for="k_msg">Nachricht *</label><textarea id="k_msg" name="nachricht" required placeholder="Ich interessiere mich für das Fahrzeug …"><?= e($_POST['nachricht'] ?? 'Ich interessiere mich für: ' . $v['titel']) ?></textarea></div>
        <label class="checkbox-row">
          <input type="checkbox" name="datenschutz" required>
          <span>Ich habe die <a href="<?= BASE_URL ?>/datenschutz.php" target="_blank">Datenschutzerklärung</a> gelesen und stimme der Verarbeitung meiner Daten zur Bearbeitung der Anfrage zu. *</span>
        </label>
        <button class="btn btn-primary btn-lg" type="submit">Nachricht senden</button>
      </form>
    </div>
  </section>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
