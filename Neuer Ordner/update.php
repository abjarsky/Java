<?php
// Verbindung zur Datenbank herstellen
$mysqli = new mysqli("localhost", "root", "", "test");

// Verbindung prüfen
if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

// Bearbeiten oder Aktualisieren basierend auf der HTTP-Methode
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Bearbeitungsformular anzeigen
    $id = $_GET['id'] ?? null;

    if ($id) {
        // Daten des Eintrags abrufen
        $result = $mysqli->query("SELECT * FROM reports WHERE id = $id");
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            die("Eintrag nicht gefunden.");
        }
    } else {
        die("Keine gültige ID angegeben.");
    }

    // Formular anzeigen
    echo "<h2>Eintrag bearbeiten</h2>";
    echo "<form method='post' action='update.php'>";
    foreach ($data as $key => $value) {
        if ($key !== 'id') { // ID nicht editierbar
            echo "<div class='form-group'>
                    <label for='$key'>" . ucfirst($key) . ":</label>
                    <input type='text' id='$key' name='$key' value='$value' class='form-control'>
                  </div>";
        }
    }
    echo "<input type='hidden' name='id' value='{$data['id']}'>";
    echo "<button type='submit' class='btn btn-success'>Änderungen speichern</button>";
    echo "</form>";
    echo "<a href='dashboard.php' class='btn btn-secondary'>Abbrechen</a>";
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST-Daten verarbeiten und speichern
    $id = $_POST['id'];
    unset($_POST['id']); // ID wird nicht aktualisiert

    $fields = [];
    foreach ($_POST as $key => $value) {
        $fields[] = "$key = '" . $mysqli->real_escape_string($value) . "'";
    }

    $sql = "UPDATE reports SET " . implode(", ", $fields) . " WHERE id = $id";

    if ($mysqli->query($sql)) {
        echo "<p>Eintrag erfolgreich aktualisiert.</p>";
    } else {
        echo "<p>Fehler beim Aktualisieren: " . $mysqli->error . "</p>";
    }

    echo "<a href='dashboard.php' class='btn btn-primary'>Zurück zum Dashboard</a>";
    echo "<a href='fetch_results.php' class='btn btn-info'>Ergebnisse anzeigen</a>";
}

$mysqli->close();
?>
