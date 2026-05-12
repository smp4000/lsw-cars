<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        // Bilder physisch löschen
        $imgs = $pdo->prepare('SELECT dateiname FROM vehicle_images WHERE vehicle_id = ?');
        $imgs->execute([$id]);
        foreach ($imgs->fetchAll(PDO::FETCH_COLUMN) as $f) {
            @unlink(UPLOAD_PATH . '/' . $f);
        }
        $pdo->prepare('DELETE FROM vehicles WHERE id = ?')->execute([$id]);
        header('Location: ' . BASE_URL . '/admin/fahrzeuge.php?msg=deleted');
        exit;
    }
}

$rows = $pdo->query('SELECT v.*, (SELECT COUNT(*) FROM vehicle_images WHERE vehicle_id=v.id) AS bilder FROM vehicles v ORDER BY erstellt_am DESC')->fetchAll();

admin_header('vehicles', 'Fahrzeuge');
?>
<div class="admin-header">
  <h1>Fahrzeuge verwalten</h1>
  <a href="<?= BASE_URL ?>/admin/fahrzeug-bearbeiten.php" class="btn btn-primary">+ Neues Fahrzeug</a>
</div>

<?php if (($_GET['msg'] ?? '') === 'deleted'): ?>
  <div class="alert alert-success">Fahrzeug wurde gelöscht.</div>
<?php elseif (($_GET['msg'] ?? '') === 'saved'): ?>
  <div class="alert alert-success">Änderungen gespeichert.</div>
<?php endif; ?>

<table class="data-table">
  <thead><tr><th>Titel</th><th>Marke</th><th>Preis</th><th>Km</th><th>Bilder</th><th>Status</th><th></th></tr></thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
      <tr>
        <td><a href="<?= BASE_URL ?>/fahrzeug.php?id=<?= (int)$r['id'] ?>" target="_blank"><?= e($r['titel']) ?></a></td>
        <td><?= e($r['marke']) ?></td>
        <td><?= fmt_price($r['preis']) ?></td>
        <td><?= e(fmt_km($r['kilometerstand'])) ?></td>
        <td><?= (int)$r['bilder'] ?></td>
        <td>
          <?php if ($r['verkauft']): ?><span class="tag tag-red">Verkauft</span>
          <?php elseif ($r['verfuegbar']): ?><span class="tag tag-green">Verfügbar</span>
          <?php else: ?><span class="tag tag-grey">Versteckt</span><?php endif; ?>
        </td>
        <td class="actions">
          <a class="btn btn-ghost" href="<?= BASE_URL ?>/admin/fahrzeug-bearbeiten.php?id=<?= (int)$r['id'] ?>">Bearbeiten</a>
          <form method="post" onsubmit="return confirm('Wirklich löschen?');" style="margin:0;">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <button class="btn btn-ghost" type="submit" style="color:#b91c1c;border-color:#fee2e4;">Löschen</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$rows): ?>
      <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--c-text-mute);">Noch keine Fahrzeuge angelegt. <a href="<?= BASE_URL ?>/admin/fahrzeug-bearbeiten.php">Jetzt erstellen</a>.</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?php admin_footer(); ?>
