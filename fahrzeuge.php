<?php
require_once __DIR__ . '/includes/vehicle.php';
$page  = 'fahrzeuge';
$title = 'Fahrzeuge';

$pdo = db();

// Filter
$marke      = trim($_GET['marke'] ?? '');
$kraftstoff = trim($_GET['kraftstoff'] ?? '');
$getriebe   = trim($_GET['getriebe'] ?? '');
$karosserie = trim($_GET['karosserie'] ?? '');
$preisMax   = (int)($_GET['preis_max'] ?? 0);
$kmMax      = (int)($_GET['km_max'] ?? 0);
$sort       = $_GET['sort'] ?? 'neu';

$where = ['verfuegbar = 1'];
$params = [];
if ($marke)      { $where[] = 'marke = ?';      $params[] = $marke; }
if ($kraftstoff) { $where[] = 'kraftstoff = ?'; $params[] = $kraftstoff; }
if ($getriebe)   { $where[] = 'getriebe = ?';   $params[] = $getriebe; }
if ($karosserie) { $where[] = 'karosserie = ?'; $params[] = $karosserie; }
if ($preisMax)   { $where[] = 'preis <= ?';     $params[] = $preisMax; }
if ($kmMax)      { $where[] = 'kilometerstand <= ?'; $params[] = $kmMax; }

$orderBy = 'erstellt_am DESC';
if ($sort === 'preis_asc')  $orderBy = 'preis ASC';
if ($sort === 'preis_desc') $orderBy = 'preis DESC';
if ($sort === 'km_asc')     $orderBy = 'kilometerstand ASC';
if ($sort === 'jahr_desc')  $orderBy = 'erstzulassung DESC';

$sql = 'SELECT * FROM vehicles WHERE ' . implode(' AND ', $where) . ' ORDER BY ' . $orderBy;
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vehicles = $stmt->fetchAll();

$brands     = $pdo->query('SELECT DISTINCT marke FROM vehicles WHERE verfuegbar=1 ORDER BY marke')->fetchAll(PDO::FETCH_COLUMN);
$bodyTypes  = $pdo->query('SELECT DISTINCT karosserie FROM vehicles WHERE verfuegbar=1 AND karosserie IS NOT NULL ORDER BY karosserie')->fetchAll(PDO::FETCH_COLUMN);

include __DIR__ . '/includes/header.php';
?>

<header class="page-header">
  <div class="container">
    <h1>Aktuelle Fahrzeuge</h1>
    <p>Stöbern Sie durch unseren aktuellen Fahrzeugbestand. Filtern Sie nach Marke, Preis und Ausstattung, um genau das passende Modell zu finden.</p>
  </div>
</header>

<div class="container">
  <form method="get" class="filter-bar">
    <div>
      <label for="marke">Marke</label>
      <select id="marke" name="marke">
        <option value="">Alle</option>
        <?php foreach ($brands as $b): ?>
          <option value="<?= e($b) ?>" <?= $marke===$b?'selected':'' ?>><?= e($b) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label for="kraftstoff">Kraftstoff</label>
      <select id="kraftstoff" name="kraftstoff">
        <option value="">Alle</option>
        <?php foreach (['Benzin','Diesel','Elektro','Hybrid','LPG','CNG'] as $k): ?>
          <option <?= $kraftstoff===$k?'selected':'' ?>><?= $k ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label for="getriebe">Getriebe</label>
      <select id="getriebe" name="getriebe">
        <option value="">Alle</option>
        <option <?= $getriebe==='Automatik'?'selected':'' ?>>Automatik</option>
        <option <?= $getriebe==='Schaltgetriebe'?'selected':'' ?>>Schaltgetriebe</option>
      </select>
    </div>
    <div>
      <label for="karosserie">Karosserie</label>
      <select id="karosserie" name="karosserie">
        <option value="">Alle</option>
        <?php foreach ($bodyTypes as $b): ?>
          <option <?= $karosserie===$b?'selected':'' ?>><?= e($b) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label for="preis_max">Preis bis (€)</label>
      <input type="number" min="0" step="500" id="preis_max" name="preis_max" value="<?= $preisMax?:'' ?>" placeholder="z. B. 25000">
    </div>
    <div>
      <label for="km_max">Km bis</label>
      <input type="number" min="0" step="1000" id="km_max" name="km_max" value="<?= $kmMax?:'' ?>" placeholder="z. B. 80000">
    </div>
    <div class="filter-actions">
      <button class="btn btn-primary" type="submit">Filtern</button>
      <a href="<?= BASE_URL ?>/fahrzeuge.php" class="btn btn-ghost">Reset</a>
    </div>
  </form>

  <div class="result-meta">
    <span><strong><?= count($vehicles) ?></strong> Fahrzeuge gefunden</span>
    <form method="get" style="display:flex;align-items:center;gap:.5rem;">
      <?php foreach ($_GET as $k => $v): if ($k==='sort') continue; ?>
        <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
      <?php endforeach; ?>
      <label for="sort" style="margin:0;">Sortieren:</label>
      <select id="sort" name="sort" onchange="this.form.submit()" style="padding:.5rem .75rem;border:1px solid var(--c-border);border-radius:var(--radius);background:#fff;">
        <option value="neu" <?= $sort==='neu'?'selected':'' ?>>Neueste zuerst</option>
        <option value="preis_asc"  <?= $sort==='preis_asc'?'selected':'' ?>>Preis aufsteigend</option>
        <option value="preis_desc" <?= $sort==='preis_desc'?'selected':'' ?>>Preis absteigend</option>
        <option value="km_asc"     <?= $sort==='km_asc'?'selected':'' ?>>Wenig Km zuerst</option>
        <option value="jahr_desc"  <?= $sort==='jahr_desc'?'selected':'' ?>>Neueste Erstzulassung</option>
      </select>
    </form>
  </div>

  <?php if (count($vehicles)): ?>
    <div class="vehicle-grid">
      <?php foreach ($vehicles as $v) render_vehicle_card($v); ?>
    </div>
  <?php else: ?>
    <div class="empty-state">
      <h3>Keine Fahrzeuge gefunden</h3>
      <p>Mit den aktuellen Filtern wurden keine Fahrzeuge gefunden. Bitte passen Sie Ihre Auswahl an.</p>
      <a href="<?= BASE_URL ?>/fahrzeuge.php" class="btn btn-primary mt-2">Filter zurücksetzen</a>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
