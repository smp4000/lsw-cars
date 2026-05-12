<?php
require_once __DIR__ . '/db.php';

function vehicle_image_url(string $file): string {
    if (preg_match('#^https?://#i', $file)) return $file;
    return UPLOAD_URL . '/' . rawurlencode($file);
}

function vehicle_first_image(int $vehicleId): ?string {
    $row = db()->prepare('SELECT dateiname FROM vehicle_images WHERE vehicle_id = ? ORDER BY sortierung, id LIMIT 1');
    $row->execute([$vehicleId]);
    $file = $row->fetchColumn();
    return $file ? vehicle_image_url($file) : null;
}

function vehicle_images(int $vehicleId): array {
    $stmt = db()->prepare('SELECT dateiname FROM vehicle_images WHERE vehicle_id = ? ORDER BY sortierung, id');
    $stmt->execute([$vehicleId]);
    $out = [];
    foreach ($stmt->fetchAll() as $row) {
        $out[] = vehicle_image_url($row['dateiname']);
    }
    return $out;
}

function vehicle_placeholder_svg(): string {
    return '<svg class="placeholder-icon" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
      <path d="M10 38l4-12a6 6 0 0 1 5.7-4.2h24.6A6 6 0 0 1 50 26l4 12v10a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2v-4H18v4a2 2 0 0 1-2 2h-4a2 2 0 0 1-2-2V38z"
        stroke="currentColor" stroke-width="2.4" stroke-linejoin="round"/>
      <circle cx="20" cy="40" r="3" fill="currentColor"/>
      <circle cx="44" cy="40" r="3" fill="currentColor"/>
    </svg>';
}

function render_vehicle_card(array $v): void {
    $img = vehicle_first_image((int)$v['id']);
    $url = BASE_URL . '/fahrzeug.php?id=' . (int)$v['id'];
    ?>
    <a class="vehicle-card" href="<?= e($url) ?>">
      <div class="v-img">
        <?php if ($img): ?>
          <img src="<?= e($img) ?>" alt="<?= e($v['titel']) ?>" loading="lazy">
        <?php else: ?>
          <?= vehicle_placeholder_svg() ?>
        <?php endif; ?>
        <?php if (!empty($v['verkauft'])): ?>
          <span class="badge badge-sold">Verkauft</span>
        <?php elseif (!empty($v['neu'])): ?>
          <span class="badge">Neu im Angebot</span>
        <?php endif; ?>
      </div>
      <div class="vehicle-body">
        <span class="brand"><?= e($v['marke']) ?></span>
        <h3><?= e($v['titel']) ?></h3>
        <div class="vehicle-specs">
          <span><?= spec_icon('calendar') ?><?= e(format_ez($v['erstzulassung'])) ?></span>
          <span><?= spec_icon('road') ?><?= e(fmt_km($v['kilometerstand'])) ?></span>
          <span><?= spec_icon('fuel') ?><?= e($v['kraftstoff'] ?? '–') ?></span>
          <span><?= spec_icon('bolt') ?><?= e($v['leistung_ps'] ? $v['leistung_ps'].' PS' : '–') ?></span>
        </div>
        <div class="vehicle-foot">
          <span class="vehicle-price"><?= fmt_price($v['preis']) ?></span>
          <span class="btn btn-ghost">Details →</span>
        </div>
      </div>
    </a>
    <?php
}

function spec_icon(string $name): string {
    switch ($name) {
      case 'calendar': return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>';
      case 'road':     return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 21l4-18M20 21l-4-18M12 4v2M12 10v2M12 16v2"/></svg>';
      case 'fuel':     return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 21V5a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v16M5 21h11M9 7h4M16 11l3 1v6a2 2 0 0 1-4 0"/></svg>';
      case 'bolt':     return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z"/></svg>';
      case 'gear':     return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5"/></svg>';
      case 'color':    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><circle cx="8" cy="9" r="1.5"/><circle cx="16" cy="9" r="1.5"/><circle cx="8" cy="15" r="1.5"/></svg>';
    }
    return '';
}

function format_ez(?string $ez): string {
    if (!$ez) return '–';
    if (preg_match('/^(\d{4})-(\d{2})/', $ez, $m)) {
        return $m[2] . '/' . $m[1];
    }
    return $ez;
}
