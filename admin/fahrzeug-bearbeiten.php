<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../includes/vehicle.php';
admin_require_login();

$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$isNew = $id === 0;

$v = [
    'marke' => '', 'modell' => '', 'titel' => '', 'beschreibung' => '',
    'preis' => 0, 'erstzulassung' => '', 'kilometerstand' => 0,
    'kraftstoff' => '', 'getriebe' => '', 'leistung_kw' => '', 'leistung_ps' => '',
    'hubraum' => '', 'farbe' => '', 'tueren' => '', 'sitze' => '',
    'karosserie' => '', 'zustand' => 'Gebraucht', 'hu' => '', 'anzahl_halter' => '',
    'klimaanlage' => 0, 'navigation' => 0, 'sitzheizung' => 0, 'einparkhilfe' => 0,
    'tempomat' => 0, 'anhaengerkupplung' => 0, 'ledersitze' => 0, 'schiebedach' => 0,
    'verfuegbar' => 1, 'verkauft' => 0,
];

if (!$isNew) {
    $stmt = $pdo->prepare('SELECT * FROM vehicles WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) {
        header('Location: ' . BASE_URL . '/admin/fahrzeuge.php');
        exit;
    }
    $v = $row;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'marke'          => trim($_POST['marke'] ?? ''),
        'modell'         => trim($_POST['modell'] ?? ''),
        'titel'          => trim($_POST['titel'] ?? ''),
        'beschreibung'   => trim($_POST['beschreibung'] ?? ''),
        'preis'          => (float)str_replace([',', '.'], ['.', ''], $_POST['preis'] ?? '0'),
        'erstzulassung'  => trim($_POST['erstzulassung'] ?? ''),
        'kilometerstand' => (int)($_POST['kilometerstand'] ?? 0),
        'kraftstoff'     => trim($_POST['kraftstoff'] ?? ''),
        'getriebe'       => trim($_POST['getriebe'] ?? ''),
        'leistung_kw'    => $_POST['leistung_kw'] !== '' ? (int)$_POST['leistung_kw'] : null,
        'leistung_ps'    => $_POST['leistung_ps'] !== '' ? (int)$_POST['leistung_ps'] : null,
        'hubraum'        => $_POST['hubraum'] !== '' ? (int)$_POST['hubraum'] : null,
        'farbe'          => trim($_POST['farbe'] ?? ''),
        'tueren'         => $_POST['tueren'] !== '' ? (int)$_POST['tueren'] : null,
        'sitze'          => $_POST['sitze'] !== '' ? (int)$_POST['sitze'] : null,
        'karosserie'     => trim($_POST['karosserie'] ?? ''),
        'zustand'        => trim($_POST['zustand'] ?? 'Gebraucht'),
        'hu'             => trim($_POST['hu'] ?? ''),
        'anzahl_halter'  => $_POST['anzahl_halter'] !== '' ? (int)$_POST['anzahl_halter'] : null,
        'klimaanlage'       => isset($_POST['klimaanlage']) ? 1 : 0,
        'navigation'        => isset($_POST['navigation']) ? 1 : 0,
        'sitzheizung'       => isset($_POST['sitzheizung']) ? 1 : 0,
        'einparkhilfe'      => isset($_POST['einparkhilfe']) ? 1 : 0,
        'tempomat'          => isset($_POST['tempomat']) ? 1 : 0,
        'anhaengerkupplung' => isset($_POST['anhaengerkupplung']) ? 1 : 0,
        'ledersitze'        => isset($_POST['ledersitze']) ? 1 : 0,
        'schiebedach'       => isset($_POST['schiebedach']) ? 1 : 0,
        'verfuegbar'        => isset($_POST['verfuegbar']) ? 1 : 0,
        'verkauft'          => isset($_POST['verkauft']) ? 1 : 0,
    ];

    if (!$data['marke'] || !$data['modell'] || !$data['titel'] || $data['preis'] <= 0) {
        $error = 'Bitte Marke, Modell, Titel und einen Preis > 0 angeben.';
        $v = array_merge($v, $data);
    } else {
        if ($isNew) {
            $cols = array_keys($data);
            $sql = 'INSERT INTO vehicles (' . implode(',', $cols) . ') VALUES (' . implode(',', array_fill(0, count($cols), '?')) . ')';
            $pdo->prepare($sql)->execute(array_values($data));
            $id = (int)$pdo->lastInsertId();
            $isNew = false;
        } else {
            $set = implode('=?,', array_keys($data)) . '=?';
            $stmt = $pdo->prepare("UPDATE vehicles SET $set WHERE id = ?");
            $stmt->execute([...array_values($data), $id]);
        }

        // Bilder hochladen
        if (!empty($_FILES['bilder']['name'][0])) {
            if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0775, true);
            $maxSort = (int)$pdo->query("SELECT COALESCE(MAX(sortierung),0) FROM vehicle_images WHERE vehicle_id=$id")->fetchColumn();
            foreach ($_FILES['bilder']['name'] as $i => $name) {
                if ($_FILES['bilder']['error'][$i] !== UPLOAD_ERR_OK) continue;
                $tmp = $_FILES['bilder']['tmp_name'][$i];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg','jpeg','png','webp','avif','gif'])) continue;
                if (filesize($tmp) > 10 * 1024 * 1024) continue;
                $fn = 'v' . $id . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
                if (move_uploaded_file($tmp, UPLOAD_PATH . '/' . $fn)) {
                    $maxSort++;
                    $pdo->prepare('INSERT INTO vehicle_images (vehicle_id, dateiname, sortierung) VALUES (?,?,?)')
                        ->execute([$id, $fn, $maxSort]);
                }
            }
        }

        // Bild löschen
        if (!empty($_POST['delete_image'])) {
            $imgId = (int)$_POST['delete_image'];
            $s = $pdo->prepare('SELECT dateiname FROM vehicle_images WHERE id = ? AND vehicle_id = ?');
            $s->execute([$imgId, $id]);
            $fn = $s->fetchColumn();
            if ($fn) {
                if (!preg_match('#^https?://#i', $fn)) {
                    @unlink(UPLOAD_PATH . '/' . $fn);
                }
                $pdo->prepare('DELETE FROM vehicle_images WHERE id = ?')->execute([$imgId]);
            }
        }

        header('Location: ' . BASE_URL . '/admin/fahrzeug-bearbeiten.php?id=' . $id . '&msg=saved');
        exit;
    }
}

$images = [];
if (!$isNew) {
    $s = $pdo->prepare('SELECT * FROM vehicle_images WHERE vehicle_id = ? ORDER BY sortierung, id');
    $s->execute([$id]);
    $images = $s->fetchAll();
}

admin_header($isNew ? 'vehicle_new' : 'vehicles', $isNew ? 'Neues Fahrzeug' : 'Fahrzeug bearbeiten');
?>
<div class="admin-header">
  <h1><?= $isNew ? 'Neues Fahrzeug' : 'Fahrzeug bearbeiten' ?></h1>
  <a href="<?= BASE_URL ?>/admin/fahrzeuge.php" class="btn btn-ghost">← zurück</a>
</div>

<?php if (($_GET['msg'] ?? '') === 'saved'): ?>
  <div class="alert alert-success">Fahrzeug erfolgreich gespeichert.</div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-error"><?= e($error) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="form-stack" style="background:#fff;padding:2rem;border-radius:var(--radius-lg);border:1px solid var(--c-border);">

  <h3 style="margin-top:0;">Grunddaten</h3>
  <div class="form-row">
    <div><label>Marke *</label><input type="text" name="marke" required value="<?= e($v['marke']) ?>"></div>
    <div><label>Modell *</label><input type="text" name="modell" required value="<?= e($v['modell']) ?>"></div>
  </div>
  <div><label>Titel (wird in Übersicht angezeigt) *</label><input type="text" name="titel" required value="<?= e($v['titel']) ?>" placeholder="z. B. BMW 320d Touring M-Paket"></div>
  <div><label>Beschreibung</label><textarea name="beschreibung" rows="6"><?= e($v['beschreibung']) ?></textarea></div>

  <div class="form-row">
    <div><label>Preis (€) *</label><input type="number" step="100" min="0" name="preis" required value="<?= e((string)(float)$v['preis']) ?>"></div>
    <div><label>Karosserie</label>
      <select name="karosserie">
        <option value="">–</option>
        <?php foreach (['Limousine','Kombi','Kleinwagen','SUV','Coupé','Cabrio','Van','Pickup','Sonstige'] as $k): ?>
          <option <?= $v['karosserie']===$k?'selected':'' ?>><?= $k ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <h3 style="margin-top:1rem;">Technische Daten</h3>
  <div class="form-row">
    <div><label>Erstzulassung (JJJJ-MM)</label><input type="text" name="erstzulassung" value="<?= e($v['erstzulassung']) ?>" placeholder="2022-06"></div>
    <div><label>Kilometerstand</label><input type="number" min="0" name="kilometerstand" value="<?= e((string)(int)$v['kilometerstand']) ?>"></div>
  </div>
  <div class="form-row">
    <div><label>Kraftstoff</label>
      <select name="kraftstoff">
        <option value="">–</option>
        <?php foreach (['Benzin','Diesel','Elektro','Hybrid','LPG','CNG'] as $k): ?>
          <option <?= $v['kraftstoff']===$k?'selected':'' ?>><?= $k ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>Getriebe</label>
      <select name="getriebe">
        <option value="">–</option>
        <option <?= $v['getriebe']==='Automatik'?'selected':'' ?>>Automatik</option>
        <option <?= $v['getriebe']==='Schaltgetriebe'?'selected':'' ?>>Schaltgetriebe</option>
      </select>
    </div>
  </div>
  <div class="form-row">
    <div><label>Leistung (kW)</label><input type="number" min="0" name="leistung_kw" value="<?= e((string)$v['leistung_kw']) ?>"></div>
    <div><label>Leistung (PS)</label><input type="number" min="0" name="leistung_ps" value="<?= e((string)$v['leistung_ps']) ?>"></div>
  </div>
  <div class="form-row">
    <div><label>Hubraum (cm³)</label><input type="number" min="0" name="hubraum" value="<?= e((string)$v['hubraum']) ?>"></div>
    <div><label>Farbe</label><input type="text" name="farbe" value="<?= e($v['farbe']) ?>"></div>
  </div>
  <div class="form-row">
    <div><label>Türen</label><input type="number" min="0" max="9" name="tueren" value="<?= e((string)$v['tueren']) ?>"></div>
    <div><label>Sitze</label><input type="number" min="0" max="9" name="sitze" value="<?= e((string)$v['sitze']) ?>"></div>
  </div>
  <div class="form-row">
    <div><label>Zustand</label>
      <select name="zustand">
        <?php foreach (['Neu','Gebraucht','Jahreswagen','Vorführwagen','Oldtimer'] as $k): ?>
          <option <?= $v['zustand']===$k?'selected':'' ?>><?= $k ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label>HU bis (MM/JJJJ)</label><input type="text" name="hu" value="<?= e($v['hu']) ?>" placeholder="06/2026"></div>
  </div>
  <div class="form-row">
    <div><label>Anzahl Halter</label><input type="number" min="0" name="anzahl_halter" value="<?= e((string)$v['anzahl_halter']) ?>"></div>
    <div></div>
  </div>

  <h3 style="margin-top:1rem;">Ausstattung</h3>
  <div class="equip-grid" style="background:var(--c-surface-2);padding:1rem 1.25rem;border-radius:var(--radius);">
    <?php foreach ([
        'klimaanlage'=>'Klimaanlage','navigation'=>'Navigation','sitzheizung'=>'Sitzheizung',
        'einparkhilfe'=>'Einparkhilfe','tempomat'=>'Tempomat','anhaengerkupplung'=>'Anhängerkupplung',
        'ledersitze'=>'Ledersitze','schiebedach'=>'Schiebedach'
      ] as $k => $label): ?>
      <label style="display:flex;gap:.5rem;align-items:center;list-style:none;">
        <input type="checkbox" name="<?= $k ?>" <?= !empty($v[$k])?'checked':'' ?>>
        <?= $label ?>
      </label>
    <?php endforeach; ?>
  </div>

  <h3 style="margin-top:1rem;">Bilder</h3>
  <?php if (!$isNew && $images): ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:1rem;">
      <?php foreach ($images as $img): ?>
        <div style="position:relative;background:var(--c-surface-2);border-radius:var(--radius);overflow:hidden;border:1px solid var(--c-border);">
          <img src="<?= e(vehicle_image_url($img['dateiname'])) ?>" alt="" style="width:100%;aspect-ratio:4/3;object-fit:cover;">
          <button type="submit" name="delete_image" value="<?= (int)$img['id'] ?>" onclick="return confirm('Bild wirklich löschen?');"
                  style="position:absolute;top:.5rem;right:.5rem;background:rgba(0,0,0,.7);color:#fff;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;">×</button>
        </div>
      <?php endforeach; ?>
    </div>
  <?php elseif (!$isNew): ?>
    <p style="color:var(--c-text-mute);">Noch keine Bilder hochgeladen.</p>
  <?php endif; ?>
  <div>
    <label>Bilder hochladen (JPG/PNG/WebP, mehrere möglich)</label>
    <input type="file" name="bilder[]" accept="image/*" multiple>
  </div>

  <h3 style="margin-top:1rem;">Status</h3>
  <div style="display:flex;gap:2rem;">
    <label class="checkbox-row"><input type="checkbox" name="verfuegbar" <?= !empty($v['verfuegbar'])?'checked':'' ?>><span>Auf Webseite anzeigen</span></label>
    <label class="checkbox-row"><input type="checkbox" name="verkauft" <?= !empty($v['verkauft'])?'checked':'' ?>><span>Bereits verkauft</span></label>
  </div>

  <div style="display:flex;gap:1rem;margin-top:1.5rem;">
    <button class="btn btn-primary btn-lg" type="submit">Speichern</button>
    <a href="<?= BASE_URL ?>/admin/fahrzeuge.php" class="btn btn-ghost">Abbrechen</a>
    <?php if (!$isNew): ?>
      <a href="<?= BASE_URL ?>/fahrzeug.php?id=<?= (int)$id ?>" target="_blank" class="btn btn-ghost" style="margin-left:auto;">Vorschau ↗</a>
    <?php endif; ?>
  </div>
</form>

<?php admin_footer(); ?>
