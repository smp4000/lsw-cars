<?php
require_once __DIR__ . '/config.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    $opts = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        // Auf Shared-Hostings (z. B. All-Inkl) hat der DB-User meist keine
        // CREATE-DATABASE-Rechte. Erst direkt verbinden – nur falls die DB
        // noch fehlt, einen Anlegeversuch unternehmen.
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Unknown database') !== false) {
            $dsnServer = 'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET;
            $server = new PDO($dsnServer, DB_USER, DB_PASS, $opts);
            $server->exec('CREATE DATABASE IF NOT EXISTS `' . DB_NAME . '` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
        } else {
            throw $e;
        }
    }

    ensure_schema($pdo);
    seed_if_empty($pdo);
    return $pdo;
}

function ensure_schema(PDO $pdo): void {
    $pdo->exec("CREATE TABLE IF NOT EXISTS vehicles (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        marke VARCHAR(80) NOT NULL,
        modell VARCHAR(120) NOT NULL,
        titel VARCHAR(200) NOT NULL,
        beschreibung TEXT,
        preis DECIMAL(10,2) NOT NULL DEFAULT 0,
        erstzulassung VARCHAR(10) DEFAULT NULL,
        kilometerstand INT UNSIGNED DEFAULT 0,
        kraftstoff VARCHAR(40) DEFAULT NULL,
        getriebe VARCHAR(40) DEFAULT NULL,
        leistung_kw INT UNSIGNED DEFAULT NULL,
        leistung_ps INT UNSIGNED DEFAULT NULL,
        hubraum INT UNSIGNED DEFAULT NULL,
        farbe VARCHAR(60) DEFAULT NULL,
        tueren TINYINT UNSIGNED DEFAULT NULL,
        sitze TINYINT UNSIGNED DEFAULT NULL,
        karosserie VARCHAR(40) DEFAULT NULL,
        zustand VARCHAR(30) DEFAULT 'Gebraucht',
        hu VARCHAR(10) DEFAULT NULL,
        anzahl_halter TINYINT UNSIGNED DEFAULT NULL,
        klimaanlage TINYINT(1) NOT NULL DEFAULT 0,
        navigation TINYINT(1) NOT NULL DEFAULT 0,
        sitzheizung TINYINT(1) NOT NULL DEFAULT 0,
        einparkhilfe TINYINT(1) NOT NULL DEFAULT 0,
        tempomat TINYINT(1) NOT NULL DEFAULT 0,
        anhaengerkupplung TINYINT(1) NOT NULL DEFAULT 0,
        ledersitze TINYINT(1) NOT NULL DEFAULT 0,
        schiebedach TINYINT(1) NOT NULL DEFAULT 0,
        verfuegbar TINYINT(1) NOT NULL DEFAULT 1,
        verkauft TINYINT(1) NOT NULL DEFAULT 0,
        erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_marke (marke),
        INDEX idx_preis (preis),
        INDEX idx_verfuegbar (verfuegbar)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS vehicle_images (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        vehicle_id INT UNSIGNED NOT NULL,
        dateiname VARCHAR(255) NOT NULL,
        sortierung INT NOT NULL DEFAULT 0,
        INDEX idx_vehicle (vehicle_id),
        CONSTRAINT fk_img_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS contact_messages (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        vehicle_id INT UNSIGNED DEFAULT NULL,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(180) NOT NULL,
        telefon VARCHAR(60) DEFAULT NULL,
        nachricht TEXT NOT NULL,
        erstellt_am TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        gelesen TINYINT(1) NOT NULL DEFAULT 0,
        INDEX idx_gelesen (gelesen)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
}

function seed_if_empty(PDO $pdo): void {
    $hasVehicles = (int)$pdo->query('SELECT COUNT(*) FROM vehicles')->fetchColumn() > 0;
    $hasImages   = (int)$pdo->query('SELECT COUNT(*) FROM vehicle_images')->fetchColumn() > 0;
    if ($hasVehicles && $hasImages) return;

    // Alte Seed-Daten ohne Bilder einmalig entfernen, damit Beispiele sauber neu kommen
    if ($hasVehicles && !$hasImages) {
        $pdo->exec('DELETE FROM vehicles');
        $pdo->exec('ALTER TABLE vehicles AUTO_INCREMENT = 1');
    }

    $u = 'https://images.unsplash.com/';
    $q = '?w=1400&q=80&auto=format&fit=crop';

    $samples = [
        [
            'marke'=>'BMW','modell'=>'320d Touring','titel'=>'BMW 320d Touring M-Sport',
            'preis'=>28900,'erstzulassung'=>'2021-03','km'=>45000,'kraftstoff'=>'Diesel','getriebe'=>'Automatik',
            'kw'=>140,'ps'=>190,'hubraum'=>1995,'farbe'=>'Saphirschwarz Metallic','tueren'=>5,'sitze'=>5,
            'karosserie'=>'Kombi','hu'=>'03/2026','halter'=>1,
            'equip'=>['klimaanlage','navigation','sitzheizung','einparkhilfe','tempomat','ledersitze'],
            'images'=>[
                $u.'photo-1555215695-3004980ad54e'.$q,
                $u.'photo-1503376780353-7e6692767b70'.$q,
                $u.'photo-1542362567-b07e54358753'.$q,
            ],
        ],
        [
            'marke'=>'Audi','modell'=>'A4 Avant','titel'=>'Audi A4 Avant 40 TFSI S line',
            'preis'=>32500,'erstzulassung'=>'2022-06','km'=>32000,'kraftstoff'=>'Benzin','getriebe'=>'Automatik',
            'kw'=>150,'ps'=>204,'hubraum'=>1984,'farbe'=>'Ibisweiß','tueren'=>5,'sitze'=>5,
            'karosserie'=>'Kombi','hu'=>'06/2026','halter'=>1,
            'equip'=>['klimaanlage','navigation','sitzheizung','einparkhilfe','tempomat','ledersitze','schiebedach'],
            'images'=>[
                $u.'photo-1606664515524-ed2f786a0bd6'.$q,
                $u.'photo-1614026480418-bd11fde2f0fd'.$q,
                $u.'photo-1612825173281-9a193378527e'.$q,
            ],
        ],
        [
            'marke'=>'Mercedes-Benz','modell'=>'C 220 d','titel'=>'Mercedes-Benz C 220 d AMG-Line',
            'preis'=>35900,'erstzulassung'=>'2022-01','km'=>28500,'kraftstoff'=>'Diesel','getriebe'=>'Automatik',
            'kw'=>147,'ps'=>200,'hubraum'=>1993,'farbe'=>'Selenitgrau Metallic','tueren'=>4,'sitze'=>5,
            'karosserie'=>'Limousine','hu'=>'01/2026','halter'=>1,
            'equip'=>['klimaanlage','navigation','sitzheizung','einparkhilfe','tempomat','ledersitze'],
            'images'=>[
                $u.'photo-1618843479313-40f8afb4b4d8'.$q,
                $u.'photo-1606664515524-ed2f786a0bd6'.$q,
                $u.'photo-1617531653332-bd46c24f2068'.$q,
            ],
        ],
        [
            'marke'=>'Volkswagen','modell'=>'Golf 8 GTI','titel'=>'VW Golf 8 GTI Performance',
            'preis'=>31900,'erstzulassung'=>'2021-09','km'=>38000,'kraftstoff'=>'Benzin','getriebe'=>'Automatik',
            'kw'=>180,'ps'=>245,'hubraum'=>1984,'farbe'=>'Tornadorot','tueren'=>5,'sitze'=>5,
            'karosserie'=>'Kleinwagen','hu'=>'09/2026','halter'=>1,
            'equip'=>['klimaanlage','navigation','sitzheizung','einparkhilfe','tempomat'],
            'images'=>[
                $u.'photo-1471444928139-48c5bf5173f8'.$q,
                $u.'photo-1503376780353-7e6692767b70'.$q,
                $u.'photo-1494976388531-d1058494cdd8'.$q,
            ],
        ],
        [
            'marke'=>'Porsche','modell'=>'Macan S','titel'=>'Porsche Macan S',
            'preis'=>64900,'erstzulassung'=>'2020-11','km'=>55000,'kraftstoff'=>'Benzin','getriebe'=>'Automatik',
            'kw'=>260,'ps'=>354,'hubraum'=>2997,'farbe'=>'Vulkangrau Metallic','tueren'=>5,'sitze'=>5,
            'karosserie'=>'SUV','hu'=>'11/2025','halter'=>2,
            'equip'=>['klimaanlage','navigation','sitzheizung','einparkhilfe','tempomat','ledersitze','schiebedach','anhaengerkupplung'],
            'images'=>[
                $u.'photo-1494976388531-d1058494cdd8'.$q,
                $u.'photo-1492144534655-ae79c964c9d7'.$q,
                $u.'photo-1503376780353-7e6692767b70'.$q,
            ],
        ],
        [
            'marke'=>'Tesla','modell'=>'Model 3','titel'=>'Tesla Model 3 Long Range AWD',
            'preis'=>38900,'erstzulassung'=>'2022-08','km'=>22000,'kraftstoff'=>'Elektro','getriebe'=>'Automatik',
            'kw'=>324,'ps'=>440,'hubraum'=>null,'farbe'=>'Pearl White Multi-Coat','tueren'=>4,'sitze'=>5,
            'karosserie'=>'Limousine','hu'=>'08/2026','halter'=>1,
            'equip'=>['klimaanlage','navigation','sitzheizung','einparkhilfe','tempomat','ledersitze','schiebedach'],
            'images'=>[
                $u.'photo-1560958089-b8a1929cea89'.$q,
                $u.'photo-1617704548623-340376564e68'.$q,
                $u.'photo-1571987502227-9231b837d92a'.$q,
            ],
        ],
    ];

    $sqlV = "INSERT INTO vehicles
        (marke, modell, titel, beschreibung, preis, erstzulassung, kilometerstand, kraftstoff, getriebe,
         leistung_kw, leistung_ps, hubraum, farbe, tueren, sitze, karosserie, hu, anzahl_halter,
         klimaanlage, navigation, sitzheizung, einparkhilfe, tempomat, anhaengerkupplung, ledersitze, schiebedach)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?, ?,?,?,?,?,?,?,?)";
    $insV = $pdo->prepare($sqlV);
    $insI = $pdo->prepare('INSERT INTO vehicle_images (vehicle_id, dateiname, sortierung) VALUES (?,?,?)');

    $desc = "Top gepflegtes Fahrzeug aus erster Hand. Scheckheftgepflegt, unfallfrei und mit kompletter Wartungshistorie. Voll ausgestattet und sofort fahrbereit. Wir freuen uns auf Ihre Anfrage – Probefahrt jederzeit möglich.";

    foreach ($samples as $s) {
        $eq = array_flip($s['equip']);
        $insV->execute([
            $s['marke'], $s['modell'], $s['titel'], $desc,
            $s['preis'], $s['erstzulassung'], $s['km'], $s['kraftstoff'], $s['getriebe'],
            $s['kw'], $s['ps'], $s['hubraum'], $s['farbe'], $s['tueren'], $s['sitze'], $s['karosserie'],
            $s['hu'], $s['halter'],
            isset($eq['klimaanlage'])?1:0,
            isset($eq['navigation'])?1:0,
            isset($eq['sitzheizung'])?1:0,
            isset($eq['einparkhilfe'])?1:0,
            isset($eq['tempomat'])?1:0,
            isset($eq['anhaengerkupplung'])?1:0,
            isset($eq['ledersitze'])?1:0,
            isset($eq['schiebedach'])?1:0,
        ]);
        $vid = (int)$pdo->lastInsertId();
        foreach ($s['images'] as $i => $img) {
            $insI->execute([$vid, $img, $i]);
        }
    }
}
