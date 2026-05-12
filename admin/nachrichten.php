<?php
require_once __DIR__ . '/auth.php';
admin_require_login();

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        if (($_POST['action'] ?? '') === 'mark_read') {
            $pdo->prepare('UPDATE contact_messages SET gelesen = 1 WHERE id = ?')->execute([$id]);
        } elseif (($_POST['action'] ?? '') === 'delete') {
            $pdo->prepare('DELETE FROM contact_messages WHERE id = ?')->execute([$id]);
        }
        header('Location: ' . BASE_URL . '/admin/nachrichten.php');
        exit;
    }
}

$rows = $pdo->query('SELECT m.*, v.titel AS fahrzeug_titel FROM contact_messages m LEFT JOIN vehicles v ON v.id = m.vehicle_id ORDER BY m.erstellt_am DESC')->fetchAll();

admin_header('messages', 'Nachrichten');
?>
<div class="admin-header"><h1>Nachrichten</h1></div>

<?php if (!$rows): ?>
  <div class="empty-state"><h3>Keine Nachrichten vorhanden</h3><p>Anfragen aus Kontakt- und Fahrzeugformularen erscheinen hier.</p></div>
<?php else: ?>
  <div style="display:grid;gap:1rem;">
    <?php foreach ($rows as $m): ?>
      <article id="m<?= (int)$m['id'] ?>" style="background:#fff;border:1px solid var(--c-border);border-radius:var(--radius-lg);padding:1.5rem;">
        <header style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
          <div>
            <strong><?= e($m['name']) ?></strong>
            <?= $m['gelesen'] ? '' : ' <span class="tag tag-green">Neu</span>' ?>
            <div style="font-size:.85rem;color:var(--c-text-mute);">
              <?= e(date('d.m.Y H:i', strtotime($m['erstellt_am']))) ?> ·
              <a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a>
              <?= $m['telefon'] ? ' · ' . e($m['telefon']) : '' ?>
              <?php if ($m['fahrzeug_titel']): ?>
                · <a href="<?= BASE_URL ?>/fahrzeug.php?id=<?= (int)$m['vehicle_id'] ?>" target="_blank">Fahrzeug: <?= e($m['fahrzeug_titel']) ?></a>
              <?php endif; ?>
            </div>
          </div>
          <div style="display:flex;gap:.5rem;">
            <?php if (!$m['gelesen']): ?>
              <form method="post" style="margin:0;"><input type="hidden" name="action" value="mark_read"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><button class="btn btn-ghost" type="submit">Als gelesen</button></form>
            <?php endif; ?>
            <form method="post" onsubmit="return confirm('Nachricht löschen?');" style="margin:0;"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$m['id'] ?>"><button class="btn btn-ghost" type="submit" style="color:#b91c1c;">Löschen</button></form>
          </div>
        </header>
        <p style="white-space:pre-line;margin:0;color:var(--c-text);"><?= e($m['nachricht']) ?></p>
      </article>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php admin_footer(); ?>
