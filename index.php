<?php
require_once __DIR__ . '/includes/vehicle.php';
$page  = 'home';
$title = 'Startseite';

$pdo = db();
$brands = $pdo->query('SELECT DISTINCT marke FROM vehicles WHERE verfuegbar=1 ORDER BY marke')->fetchAll(PDO::FETCH_COLUMN);
$featured = $pdo->query('SELECT * FROM vehicles WHERE verfuegbar=1 AND verkauft=0 ORDER BY erstellt_am DESC LIMIT 6')->fetchAll();
$totalCars = (int)$pdo->query('SELECT COUNT(*) FROM vehicles WHERE verfuegbar=1')->fetchColumn();

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="container hero-inner">
    <div>
      <span class="hero-eyebrow">Premium-Fahrzeuge · Sofort verfügbar</span>
      <h1>Ihr Traumauto bei<br><em>LSW Cars</em></h1>
      <p class="lead">
        Geprüfte Gebrauchtwagen und Premium-Modelle – mit voller Transparenz,
        fairen Preisen und persönlicher Beratung. Wir bringen Sie ans Steuer.
      </p>
      <div class="hero-actions">
        <a href="<?= BASE_URL ?>/fahrzeuge.php" class="btn btn-primary btn-lg">Jetzt Fahrzeuge entdecken</a>
        <a href="<?= BASE_URL ?>/kontakt.php" class="btn btn-ghost btn-lg" style="color:#fff;border-color:rgba(255,255,255,.25)">Beratung anfragen</a>
      </div>
      <div class="hero-stats">
        <div class="stat"><strong><?= $totalCars ?>+</strong><span>Fahrzeuge im Bestand</span></div>
        <div class="stat"><strong>15</strong><span>Jahre Erfahrung</span></div>
        <div class="stat"><strong>4.9★</strong><span>Kundenbewertung</span></div>
      </div>
    </div>
    <div class="hero-visual" aria-hidden="true">
      <img src="<?= BASE_URL ?>/assets/images/amg-gts.jpg" alt="Mercedes-AMG GT S">
    </div>
  </div>
</section>

<div class="container">
  <form class="search-card" action="<?= BASE_URL ?>/fahrzeuge.php" method="get">
    <div>
      <label for="f_marke">Marke</label>
      <select name="marke" id="f_marke">
        <option value="">Alle Marken</option>
        <?php foreach ($brands as $b): ?>
          <option value="<?= e($b) ?>"><?= e($b) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label for="f_kraft">Kraftstoff</label>
      <select name="kraftstoff" id="f_kraft">
        <option value="">Alle</option>
        <option>Benzin</option>
        <option>Diesel</option>
        <option>Elektro</option>
        <option>Hybrid</option>
      </select>
    </div>
    <div>
      <label for="f_max">Preis bis</label>
      <select name="preis_max" id="f_max">
        <option value="">Beliebig</option>
        <option value="10000">10.000 €</option>
        <option value="20000">20.000 €</option>
        <option value="30000">30.000 €</option>
        <option value="50000">50.000 €</option>
        <option value="100000">100.000 €</option>
      </select>
    </div>
    <div>
      <label for="f_km">Km bis</label>
      <select name="km_max" id="f_km">
        <option value="">Beliebig</option>
        <option value="20000">20.000</option>
        <option value="50000">50.000</option>
        <option value="100000">100.000</option>
        <option value="150000">150.000</option>
      </select>
    </div>
    <button class="btn btn-primary btn-lg" type="submit">Suchen</button>
  </form>
</div>

<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Ausgewählte Fahrzeuge</span>
      <h2>Unsere Highlights</h2>
      <p>Eine Auswahl aus unserem aktuellen Angebot – sorgfältig geprüft und sofort fahrbereit.</p>
    </div>

    <?php if (count($featured)): ?>
      <div class="vehicle-grid">
        <?php foreach ($featured as $v) render_vehicle_card($v); ?>
      </div>
      <div class="text-center mt-3">
        <a href="<?= BASE_URL ?>/fahrzeuge.php" class="btn btn-dark btn-lg">Alle Fahrzeuge ansehen →</a>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <h3>Aktuell sind keine Fahrzeuge online.</h3>
        <p>Bitte schauen Sie bald wieder vorbei oder kontaktieren Sie uns direkt.</p>
        <a href="<?= BASE_URL ?>/kontakt.php" class="btn btn-primary mt-2">Kontakt aufnehmen</a>
      </div>
    <?php endif; ?>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Warum LSW Cars</span>
      <h2>Vertrauen, das Sie bewegt</h2>
    </div>
    <div class="feature-grid">
      <div class="feature">
        <div class="icon"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <h3>Geprüfte Qualität</h3>
        <p>Jedes Fahrzeug wird vor dem Verkauf umfassend technisch geprüft – transparent und nachvollziehbar.</p>
      </div>
      <div class="feature">
        <div class="icon"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></div>
        <h3>Schnelle Abwicklung</h3>
        <p>Probefahrt, Finanzierung und Übergabe – persönlich und unkompliziert innerhalb weniger Tage.</p>
      </div>
      <div class="feature">
        <div class="icon"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4 2 7L12 16l-6.5 4 2-7L2 9h7z"/></svg></div>
        <h3>Faire Preise</h3>
        <p>Marktgerechte Preise mit Inzahlungnahme Ihres Altfahrzeugs – auf Wunsch inklusive Garantieverlängerung.</p>
      </div>
      <div class="feature">
        <div class="icon"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l9-8 9 8M5 10v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V10"/></svg></div>
        <h3>Persönliche Beratung</h3>
        <p>Wir nehmen uns Zeit für Sie – ehrlich, kompetent und ohne Verkaufsdruck.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Unsere Leistungen</span>
      <h2>Mehr als nur Autohandel</h2>
      <p>Vom Premium-Gebrauchten bis zur professionellen Innenreinigung – wir kümmern uns um Ihr Fahrzeug.</p>
    </div>

    <div class="service-grid">
      <div class="service-card">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12l3 3 3-3-3-3-3 3z"/><path d="M14 5l4 4-8 8H6v-4l8-8z"/></svg>
        </div>
        <h3>Professionelle Innenreinigung</h3>
        <p>Gründliche Aufbereitung Ihres Fahrzeuginnenraums – Sitze, Cockpit, Teppiche und Verkleidungen.</p>
        <div class="price-tag">ab 50 €<br><small>Auf Wunsch mit Außenwäsche</small></div>
        <a href="<?= BASE_URL ?>/leistungen.php" class="btn btn-ghost mt-2">Mehr erfahren →</a>
      </div>
      <div class="service-card">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 17h14M5 7h14M5 12h14"/><circle cx="12" cy="12" r="9"/></svg>
        </div>
        <h3>Fahrzeug-Verkauf</h3>
        <p>Geprüfte Premium-Fahrzeuge zu fairen Preisen – mit voller Transparenz und persönlicher Beratung.</p>
        <div class="price-tag">Top-Auswahl</div>
        <a href="<?= BASE_URL ?>/fahrzeuge.php" class="btn btn-ghost mt-2">Zum Bestand →</a>
      </div>
      <div class="service-card">
        <div class="icon-circle">
          <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M2 12h4M18 12h4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
        </div>
        <h3>Ankauf Ihres Fahrzeugs</h3>
        <p>Schnell, fair und unkompliziert – verbindliches Angebot innerhalb von 24 Stunden. Auch Bargeld-Ankauf möglich.</p>
        <div class="price-tag">Bestpreis</div>
        <a href="<?= BASE_URL ?>/kontakt.php" class="btn btn-ghost mt-2">Angebot anfordern →</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
