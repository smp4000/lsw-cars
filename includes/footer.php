</main>

<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <div class="logo logo-footer">
        <?= lsw_logo_svg('full') ?>
      </div>
      <p class="footer-tag" style="margin-top:1rem;"><?= e(SITE_TAGLINE) ?></p>
    </div>
    <div>
      <h4>Kontakt</h4>
      <p>
        <?= e(COMPANY_NAME) ?><br>
        <?= e(COMPANY_STREET) ?><br>
        <?= e(COMPANY_ZIP) ?> <?= e(COMPANY_CITY) ?>
      </p>
      <p>
        Tel: <a href="tel:<?= e(preg_replace('/\s+/', '', COMPANY_PHONE)) ?>"><?= e(COMPANY_PHONE) ?></a><br>
        E-Mail: <a href="mailto:<?= e(COMPANY_EMAIL) ?>"><?= e(COMPANY_EMAIL) ?></a>
      </p>
    </div>
    <div>
      <h4>Öffnungszeiten</h4>
      <p>Mo–Fr: 9:00 – 18:00<br>
      Sa: 10:00 – 14:00<br>
      So: geschlossen</p>
    </div>
    <div>
      <h4>Service</h4>
      <ul class="footer-links">
        <li><a href="<?= BASE_URL ?>/fahrzeuge.php">Fahrzeuge</a></li>
        <li><a href="<?= BASE_URL ?>/ueber-uns.php">Über uns</a></li>
        <li><a href="<?= BASE_URL ?>/kontakt.php">Kontakt</a></li>
        <li><a href="<?= BASE_URL ?>/admin/">Admin</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bar">
    <div class="container footer-bar-inner">
      <span>© <?= date('Y') ?> <?= e(COMPANY_NAME) ?>. Alle Rechte vorbehalten.</span>
      <nav class="footer-legal">
        <a href="<?= BASE_URL ?>/impressum.php">Impressum</a>
        <a href="<?= BASE_URL ?>/datenschutz.php">Datenschutz</a>
        <a href="#" id="openCookieSettings">Cookie-Einstellungen</a>
      </nav>
    </div>
  </div>
</footer>

<!-- Cookie-Banner -->
<div class="cookie-banner" id="cookieBanner" role="dialog" aria-labelledby="cookieTitle" hidden>
  <div class="cookie-inner">
    <div class="cookie-text">
      <h3 id="cookieTitle">Wir respektieren Ihre Privatsphäre</h3>
      <p>Diese Webseite verwendet ausschließlich technisch notwendige Cookies, um eine sichere und komfortable Nutzung zu ermöglichen. Optionale Cookies setzen wir nur mit Ihrer Einwilligung. Weitere Informationen finden Sie in unserer <a href="<?= BASE_URL ?>/datenschutz.php">Datenschutzerklärung</a>.</p>
    </div>
    <div class="cookie-actions">
      <button type="button" class="btn btn-ghost" data-cookie="essential">Nur notwendige</button>
      <button type="button" class="btn btn-primary" data-cookie="all">Alle akzeptieren</button>
    </div>
  </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/main.js" defer></script>
</body>
</html>
