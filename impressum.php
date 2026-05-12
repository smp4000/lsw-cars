<?php
require_once __DIR__ . '/includes/config.php';
$page = 'impressum';
$title = 'Impressum';
include __DIR__ . '/includes/header.php';
?>

<header class="page-header">
  <div class="container">
    <h1>Impressum</h1>
    <p>Angaben gemäß § 5 TMG</p>
  </div>
</header>

<div class="container section" style="padding-top:2rem;">
  <div class="prose">

    <h2>Anbieter</h2>
    <p>
      <strong><?= e(COMPANY_NAME) ?></strong><br>
      <?= e(COMPANY_STREET) ?><br>
      <?= e(COMPANY_ZIP) ?> <?= e(COMPANY_CITY) ?>
    </p>

    <h2>Vertreten durch</h2>
    <p><?= e(COMPANY_OWNER) ?></p>

    <h2>Kontakt</h2>
    <p>
      Telefon: <a href="tel:<?= e(preg_replace('/\s+/', '', COMPANY_PHONE)) ?>"><?= e(COMPANY_PHONE) ?></a><br>
      E-Mail: <a href="mailto:<?= e(COMPANY_EMAIL) ?>"><?= e(COMPANY_EMAIL) ?></a>
    </p>

    <h2>Registereintrag</h2>
    <p>
      Eintragung im Handelsregister<br>
      Registergericht: <?= e(COMPANY_COURT) ?><br>
      Registernummer: <?= e(COMPANY_HRB) ?>
    </p>

    <h2>Umsatzsteuer-ID</h2>
    <p>
      Umsatzsteuer-Identifikationsnummer gemäß § 27a Umsatzsteuergesetz:<br>
      <?= e(COMPANY_VAT) ?>
    </p>

    <h2>Verantwortlich für den Inhalt nach § 55 Abs. 2 RStV</h2>
    <p>
      <?= e(COMPANY_OWNER) ?><br>
      <?= e(COMPANY_STREET) ?><br>
      <?= e(COMPANY_ZIP) ?> <?= e(COMPANY_CITY) ?>
    </p>

    <h2>EU-Streitschlichtung</h2>
    <p>
      Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung (OS) bereit:
      <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noopener">https://ec.europa.eu/consumers/odr</a>.<br>
      Unsere E-Mail-Adresse finden Sie oben im Impressum.
    </p>

    <h2>Verbraucher­streit­beilegung / Universal­schlichtungs­stelle</h2>
    <p>Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle teilzunehmen.</p>

    <h2>Haftung für Inhalte</h2>
    <p>Als Diensteanbieter sind wir gemäß § 7 Abs. 1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen.</p>

    <h2>Haftung für Links</h2>
    <p>Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber verantwortlich.</p>

    <h2>Urheberrecht</h2>
    <p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechts bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.</p>

  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
