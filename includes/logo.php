<?php
/**
 * LSW Cars Logo
 *
 * Wenn unter /assets/images/ eine Datei logo.png / logo.jpg / logo.webp / logo.svg
 * (mit transparentem Hintergrund) existiert, wird diese verwendet.
 * Sonst rendert die Funktion ein hochwertiges Inline-SVG (transparent).
 *
 * $variant: "compact" (Header) | "full" (Footer/Login)
 */
function lsw_logo_svg(string $variant = 'full'): string {
    foreach (['svg','png','webp','jpg','jpeg'] as $ext) {
        $file = __DIR__ . '/../assets/images/logo.' . $ext;
        if (is_file($file)) {
            $url = BASE_URL . '/assets/images/logo.' . $ext . '?v=' . filemtime($file);
            return '<img class="logo-svg" src="' . e($url) . '" alt="LSW Cars">';
        }
    }
    return lsw_logo_inline_svg($variant);
}

function lsw_logo_inline_svg(string $variant = 'full'): string {
    $defs = <<<DEFS
<defs>
  <!-- Vertikaler Chrome-Verlauf für Buchstaben (heller oben, dunkler unten, helles Reflex-Band in der Mitte) -->
  <linearGradient id="chrome" x1="0" y1="0" x2="0" y2="1">
    <stop offset="0%"   stop-color="#f8fafc"/>
    <stop offset="22%"  stop-color="#cfd5da"/>
    <stop offset="48%"  stop-color="#7d848b"/>
    <stop offset="52%"  stop-color="#9aa1a8"/>
    <stop offset="75%"  stop-color="#dde2e7"/>
    <stop offset="100%" stop-color="#5b6168"/>
  </linearGradient>
  <!-- Chrome-Verlauf für Linien (Auto-Silhouette) -->
  <linearGradient id="chromeLine" x1="0" y1="0" x2="0" y2="1">
    <stop offset="0%"   stop-color="#ffffff"/>
    <stop offset="50%"  stop-color="#a8aeb4"/>
    <stop offset="100%" stop-color="#5b6168"/>
  </linearGradient>
  <!-- Soft chrome für „CARS" und Trennlinien -->
  <linearGradient id="chromeSoft" x1="0" y1="0" x2="0" y2="1">
    <stop offset="0%"   stop-color="#e3e7ec"/>
    <stop offset="100%" stop-color="#8a9097"/>
  </linearGradient>
  <!-- Sanfter Schein zur Strukturhebung -->
  <filter id="softGlow" x="-10%" y="-10%" width="120%" height="120%">
    <feGaussianBlur stdDeviation="0.4" result="b"/>
    <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
  </filter>
</defs>
DEFS;

    // Sportwagen-Silhouette aus mehreren flowing Linien (Dach, Fenster, Body, Schweller)
    $car = <<<CAR
<g fill="none" stroke="url(#chromeLine)" stroke-linecap="round" stroke-linejoin="round" filter="url(#softGlow)">
  <!-- Hauptdachlinie (große Wölbung) -->
  <path d="M 18 50 C 60 8, 150 0, 220 12 C 252 18, 268 32, 286 44" stroke-width="2.4"/>
  <!-- A-/C-Säulen-Bogen darunter -->
  <path d="M 60 36 C 100 18, 180 14, 230 26" stroke-width="1.6" opacity=".92"/>
  <!-- Fensterkante -->
  <path d="M 78 30 C 120 18, 180 18, 220 28" stroke-width="1.1" opacity=".75"/>
  <!-- Seitenlinie über Türgriff -->
  <path d="M 10 54 C 80 42, 200 42, 290 50" stroke-width="1.4" opacity=".9"/>
  <!-- Schweller / untere Karosseriekante -->
  <path d="M 6 60 L 294 60" stroke-width="2" opacity=".95"/>
  <!-- Heckspoiler-Andeutung -->
  <path d="M 268 36 C 282 38, 290 44, 292 50" stroke-width="1.4"/>
  <!-- Vorderer Kotflügel-Akzent -->
  <path d="M 36 56 C 48 42, 62 38, 80 38" stroke-width="1.2" opacity=".7"/>
  <!-- Hinterer Kotflügel-Akzent -->
  <path d="M 218 38 C 240 38, 258 46, 268 56" stroke-width="1.2" opacity=".7"/>
  <!-- Türgriff-/Seitenlüftungs-Strich -->
  <line x1="146" y1="36" x2="158" y2="36" stroke-width="1.2" opacity=".7"/>
</g>
<!-- Radkasten-Andeutungen (Halbkreis-Bögen) -->
<g fill="none" stroke="url(#chromeSoft)" stroke-width="1.6" stroke-linecap="round" opacity=".85">
  <path d="M 56 60 C 62 50, 86 50, 92 60"/>
  <path d="M 210 60 C 216 50, 240 50, 246 60"/>
</g>
CAR;

    if ($variant === 'compact') {
        return <<<SVG
<svg class="logo-svg" viewBox="0 0 300 130" xmlns="http://www.w3.org/2000/svg" aria-label="LSW Cars" preserveAspectRatio="xMidYMid meet">
  $defs
  <!-- Auto-Silhouette oben mittig -->
  <g transform="translate(0,0) scale(1)">$car</g>

  <!-- LSW Hauptschriftzug -->
  <text x="150" y="108" text-anchor="middle"
        fill="url(#chrome)"
        font-family="'Plus Jakarta Sans','Inter',sans-serif"
        font-weight="800" font-size="44" letter-spacing="6">LSW</text>

  <!-- CARS + Trennstriche -->
  <line x1="86"  y1="124" x2="125" y2="124" stroke="url(#chromeSoft)" stroke-width="1.2"/>
  <text x="150" y="126" text-anchor="middle"
        fill="url(#chromeSoft)"
        font-family="'Plus Jakarta Sans','Inter',sans-serif"
        font-weight="600" font-size="11" letter-spacing="10">CARS</text>
  <line x1="175" y1="124" x2="214" y2="124" stroke="url(#chromeSoft)" stroke-width="1.2"/>
</svg>
SVG;
    }

    // Full
    return <<<SVG
<svg class="logo-svg" viewBox="0 0 320 150" xmlns="http://www.w3.org/2000/svg" aria-label="LSW Cars" preserveAspectRatio="xMidYMid meet">
  $defs
  <g transform="translate(10,0)">$car</g>

  <text x="160" y="120" text-anchor="middle"
        fill="url(#chrome)"
        font-family="'Plus Jakarta Sans','Inter',sans-serif"
        font-weight="800" font-size="54" letter-spacing="8">LSW</text>

  <line x1="80"  y1="138" x2="130" y2="138" stroke="url(#chromeSoft)" stroke-width="1.4"/>
  <text x="160" y="142" text-anchor="middle"
        fill="url(#chromeSoft)"
        font-family="'Plus Jakarta Sans','Inter',sans-serif"
        font-weight="600" font-size="13" letter-spacing="12">CARS</text>
  <line x1="190" y1="138" x2="240" y2="138" stroke="url(#chromeSoft)" stroke-width="1.4"/>
</svg>
SVG;
}
