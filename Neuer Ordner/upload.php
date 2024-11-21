<?php
// Load database connection
$mysqli = new mysqli("localhost", "root", "", "test");

// Check connection
if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

// Daten aus POST verarbeiten
$firstname = $_POST['firstname'] ?? null;
$lastname = $_POST['lastname'] ?? null;
$address = $_POST['address'] ?? null;
$zipcode = $_POST['zipcode'] ?? null;
$city = $_POST['city'] ?? null;
$project = $_POST['project'] ?? null;
$date = $_POST['date'] ?? null;
$start_time = $_POST['start_time'] ?? null;
$end_time = $_POST['end_time'] ?? null;
$pause_time = $_POST['pause_time'] ?? 0;

// Gesamtzeit berechnen
function calculateTotalTime($start, $end, $pause) {
    $startTimestamp = strtotime($start);
    $endTimestamp = strtotime($end);
    if (!$startTimestamp || !$endTimestamp) {
        return 0;
    }
    $duration = ($endTimestamp - $startTimestamp) / 60; // Minuten
    return $duration - $pause; // Pause abziehen
}
$total_time = calculateTotalTime($start_time, $end_time, $pause_time);

// SQL-Abfrage vorbereiten
$sql = "INSERT INTO reports (firstname, lastname, address, zipcode, city, project, date, total_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    die("Fehler beim Erstellen der SQL-Abfrage: " . $mysqli->error);
}

// Parameter binden
$stmt->bind_param(
    "ssssssss",  // Typen für die Parameter
    $firstname, $lastname, $address, $zipcode, $city, $project, $date, $total_time
);

// Abfrage ausführen
if (!$stmt->execute()) {
    die("Fehler beim Einfügen: " . $stmt->error);
}

// ID des neuen Eintrags abrufen
$new_id = $mysqli->insert_id;

// Daten des neuen Eintrags abrufen
$result = $mysqli->query("SELECT * FROM reports WHERE id = $new_id");
$data = $result->fetch_assoc();

// Zusammenfassung anzeigen
echo "<h2>Zusammenfassung des Eintrags</h2>";
echo "<form method='post' action='update.php'>";
foreach ($data as $key => $value) {
    echo "<div class='form-group'>
            <label for='$key'>" . ucfirst($key) . ":</label>
            <input type='text' id='$key' name='$key' value='$value' class='form-control'>
          </div>";
}
echo "<input type='hidden' name='id' value='{$new_id}'>";
echo "<button type='submit' class='btn btn-primary'>Änderungen speichern</button>";
echo "</form>";

// Zurück zum Dashboard und Ergebnissanzeige
echo "<a href='dashboard.php' class='btn btn-secondary'>Zurück zum Dashboard</a>";
echo "<a href='fetch_results.php' class='btn btn-info'>Ergebnisse anzeigen</a>";

$stmt->close();
$mysqli->close();
?>
