<?php
require_once __DIR__ . '/includes/config.php';
$page = 'datenschutz';
$title = 'Datenschutz';
include __DIR__ . '/includes/header.php';
?>

<header class="page-header">
  <div class="container">
    <h1>Datenschutzerklärung</h1>
    <p>Informationen zur Verarbeitung Ihrer personenbezogenen Daten gemäß Art. 13 DSGVO.</p>
  </div>
</header>

<div class="container section" style="padding-top:2rem;">
  <div class="prose">

    <h2>1. Verantwortlicher</h2>
    <p>
      Verantwortlich im Sinne der DSGVO ist:<br>
      <strong><?= e(COMPANY_NAME) ?></strong><br>
      <?= e(COMPANY_STREET) ?>, <?= e(COMPANY_ZIP) ?> <?= e(COMPANY_CITY) ?><br>
      Telefon: <?= e(COMPANY_PHONE) ?><br>
      E-Mail: <?= e(COMPANY_EMAIL) ?>
    </p>

    <h2>2. Erhebung allgemeiner Informationen</h2>
    <p>Beim Aufruf unserer Webseite werden durch unseren Webserver automatisch Informationen technischer Art erfasst (z. B. Browsertyp, Betriebssystem, Domainname, IP-Adresse, abgerufene URL, Datum und Uhrzeit). Diese Daten sind nicht bestimmten Personen zuordenbar. Sie werden ausschließlich zur statistischen Auswertung sowie zur Verbesserung unseres Angebots verarbeitet. Eine Zusammenführung mit anderen Datenquellen findet nicht statt.</p>
    <p>Rechtsgrundlage ist Art. 6 Abs. 1 lit. f DSGVO (berechtigtes Interesse an einem sicheren und stabilen Betrieb).</p>

    <h2>3. Cookies</h2>
    <p>Wir setzen ausschließlich technisch notwendige Cookies (z. B. zur Speicherung Ihrer Cookie-Einwilligung). Optionale Cookies werden nur mit Ihrer ausdrücklichen Einwilligung verwendet. Sie können Ihre Einwilligung jederzeit über die <a href="#" id="reopenCookies">Cookie-Einstellungen</a> widerrufen.</p>
    <p>Rechtsgrundlage: § 25 Abs. 2 TTDSG (für technisch notwendige Cookies) bzw. § 25 Abs. 1 TTDSG i. V. m. Art. 6 Abs. 1 lit. a DSGVO (für optionale Cookies).</p>

    <h2>4. Kontaktformulare und Fahrzeuganfragen</h2>
    <p>Wenn Sie uns über das Kontaktformular oder über eine Fahrzeug-Anfrage kontaktieren, werden Ihre Angaben (Name, E-Mail, Telefon, Nachricht) zur Bearbeitung der Anfrage und für etwaige Anschlussfragen gespeichert. Diese Daten werden nicht an Dritte weitergegeben.</p>
    <p>Rechtsgrundlage: Art. 6 Abs. 1 lit. b DSGVO (vorvertragliche Maßnahmen) bzw. lit. f DSGVO. Die Daten werden gelöscht, sobald der Zweck der Verarbeitung entfällt – spätestens nach Ablauf gesetzlicher Aufbewahrungsfristen.</p>

    <h2>5. Webhosting</h2>
    <p>Unsere Webseite wird auf Servern eines spezialisierten Hosting-Anbieters betrieben. Mit dem Anbieter besteht ein Auftragsverarbeitungsvertrag gemäß Art. 28 DSGVO.</p>

    <h2>6. Ihre Rechte</h2>
    <p>Sie haben jederzeit das Recht auf:</p>
    <ul>
      <li>Auskunft über die Sie betreffenden personenbezogenen Daten (Art. 15 DSGVO),</li>
      <li>Berichtigung unrichtiger Daten (Art. 16 DSGVO),</li>
      <li>Löschung Ihrer Daten (Art. 17 DSGVO),</li>
      <li>Einschränkung der Verarbeitung (Art. 18 DSGVO),</li>
      <li>Datenübertragbarkeit (Art. 20 DSGVO),</li>
      <li>Widerruf einer erteilten Einwilligung (Art. 7 Abs. 3 DSGVO),</li>
      <li>Widerspruch gegen die Verarbeitung (Art. 21 DSGVO).</li>
    </ul>
    <p>Zur Ausübung Ihrer Rechte genügt eine formlose E-Mail an <?= e(COMPANY_EMAIL) ?>.</p>

    <h2>7. Beschwerderecht bei der Aufsichtsbehörde</h2>
    <p>Sie haben das Recht, sich bei einer Datenschutz-Aufsichtsbehörde über die Verarbeitung Ihrer personenbezogenen Daten zu beschweren.</p>

    <h2>8. Datensicherheit</h2>
    <p>Wir setzen technische und organisatorische Maßnahmen ein, um Ihre Daten gegen Manipulation, Verlust und unbefugten Zugriff zu schützen. Unsere Sicherheitsmaßnahmen werden entsprechend der technologischen Entwicklung fortlaufend angepasst.</p>

    <h2>9. Aktualität dieser Datenschutzerklärung</h2>
    <p>Diese Datenschutzerklärung ist aktuell gültig. Durch die Weiterentwicklung unserer Webseite oder geänderte gesetzliche Vorgaben kann eine Anpassung erforderlich werden.</p>

  </div>
</div>

<script>
  document.getElementById('reopenCookies')?.addEventListener('click', (e) => {
    e.preventDefault();
    const b = document.getElementById('cookieBanner');
    if (b) b.hidden = false;
  });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
