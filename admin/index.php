<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pdo = db();
$total      = (int)$pdo->query('SELECT COUNT(*) FROM vehicles')->fetchColumn();
$avail      = (int)$pdo->query('SELECT COUNT(*) FROM vehicles WHERE verfuegbar=1 AND verkauft=0')->fetchColumn();
$sold       = (int)$pdo->query('SELECT COUNT(*) FROM vehicles WHERE verkauft=1')->fetchColumn();
$messages   = (int)$pdo->query('SELECT COUNT(*) FROM contact_messages WHERE gelesen=0')->fetchColumn();
$recent     = $pdo->query('SELECT * FROM vehicles ORDER BY erstellt_am DESC LIMIT 5')->fetchAll();
$lastMsg    = $pdo->query('SELECT * FROM contact_messages ORDER BY erstellt_am DESC LIMIT 5')->fetchAll();

admin_header('dashboard', 'Dashboard');
?>
<div class="admin-header">
  <h1>Dashboard</h1>
  <a href="<?= BASE_URL ?>/admin/fahrzeug-bearbeiten.php" class="btn btn-primary">+ Neues Fahrzeug</a>
</div>

<div class="kpi-grid">
  <div class="kpi"><small>Fahrzeuge gesamt</small><strong><?= $total ?></strong></div>
  <div class="kpi"><small>Aktuell verfügbar</small><strong><?= $avail ?></strong></div>
  <div class="kpi"><small>Verkauft</small><strong><?= $sold ?></strong></div>
  <div class="kpi"><small>Ungelesene Anfragen</small><strong><?= $messages ?></strong></div>
</div>

<h2 style="font-size:1.2rem;margin:2rem 0 1rem;">Zuletzt eingestellte Fahrzeuge</h2>
<table class="data-table">
  <thead><tr><th>Titel</th><th>Marke</th><th>Preis</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($recent as $r): ?>
      <tr>
        <td><?= e($r['titel']) ?></td>
        <td><?= e($r['marke']) ?></td>
        <td><?= fmt_price($r['preis']) ?></td>
        <td>
          <?php if ($r['verkauft']): ?><span class="tag tag-red">Verkauft</span>
          <?php elseif ($r['verfuegbar']): ?><span class="tag tag-green">Verfügbar</span>
          <?php else: ?><span class="tag tag-grey">Versteckt</span><?php endif; ?>
        </td>
        <td><a class="btn btn-ghost" href="<?= BASE_URL ?>/admin/fahrzeug-bearbeiten.php?id=<?= (int)$r['id'] ?>">Bearbeiten</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$recent): ?><tr><td colspan="5" style="text-align:center;color:var(--c-text-mute);padding:2rem;">Noch keine Fahrzeuge erfasst.</td></tr><?php endif; ?>
  </tbody>
</table>

<h2 style="font-size:1.2rem;margin:2rem 0 1rem;">Letzte Nachrichten</h2>
<table class="data-table">
  <thead><tr><th>Zeitpunkt</th><th>Name</th><th>E-Mail</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($lastMsg as $m): ?>
      <tr>
        <td><?= e(date('d.m.Y H:i', strtotime($m['erstellt_am']))) ?></td>
        <td><?= e($m['name']) ?></td>
        <td><?= e($m['email']) ?></td>
        <td><?= $m['gelesen'] ? '<span class="tag tag-grey">Gelesen</span>' : '<span class="tag tag-green">Neu</span>' ?></td>
        <td><a class="btn btn-ghost" href="<?= BASE_URL ?>/admin/nachrichten.php#m<?= (int)$m['id'] ?>">Ansehen</a></td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$lastMsg): ?><tr><td colspan="5" style="text-align:center;color:var(--c-text-mute);padding:2rem;">Noch keine Nachrichten.</td></tr><?php endif; ?>
  </tbody>
</table>

<?php admin_footer(); ?>
