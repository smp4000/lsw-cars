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

<!-- WhatsApp Floating Action Button -->
<a class="whatsapp-fab"
   href="https://wa.me/<?= e(COMPANY_WHATSAPP) ?>?text=<?= rawurlencode('Hallo LSW Cars, ich habe eine Anfrage.') ?>"
   target="_blank" rel="noopener noreferrer"
   aria-label="Per WhatsApp Kontakt aufnehmen"
   title="Schreiben Sie uns auf WhatsApp">
  <svg viewBox="0 0 32 32" width="26" height="26" fill="currentColor" aria-hidden="true">
    <path d="M16.001 3C9.373 3 4 8.374 4 15.002c0 2.643.86 5.094 2.323 7.092L4 29l7.1-2.272a11.94 11.94 0 0 0 4.901 1.045c6.628 0 12.002-5.373 12.002-12.001 0-6.629-5.374-12.002-12.002-12.002zm0 21.945a9.93 9.93 0 0 1-4.957-1.32l-.355-.21-4.214 1.348 1.366-4.105-.231-.366a9.917 9.917 0 0 1-1.524-5.293c0-5.482 4.463-9.946 9.945-9.946 5.482 0 9.946 4.464 9.946 9.946 0 5.481-4.464 9.946-9.946 9.946zm5.453-7.444c-.298-.149-1.764-.872-2.038-.972-.273-.1-.472-.149-.671.149-.198.298-.768.972-.94 1.171-.173.198-.347.223-.645.074-.298-.149-1.26-.464-2.402-1.481-.888-.793-1.487-1.773-1.66-2.072-.174-.298-.018-.46.13-.609.134-.134.298-.347.447-.521.149-.174.198-.298.298-.496.099-.198.05-.371-.025-.52-.075-.149-.671-1.616-.92-2.213-.242-.582-.488-.503-.671-.512l-.571-.01a1.094 1.094 0 0 0-.794.371c-.273.298-1.04 1.016-1.04 2.477 0 1.461 1.064 2.872 1.213 3.07.149.198 2.094 3.2 5.075 4.49.71.306 1.263.488 1.696.625.712.226 1.36.194 1.872.118.571-.085 1.764-.72 2.014-1.418.248-.696.248-1.293.174-1.418-.075-.124-.273-.198-.571-.347z"/>
  </svg>
  <span class="whatsapp-fab-text">WhatsApp</span>
</a>

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
