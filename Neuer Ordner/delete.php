<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

// Verbindung zur Datenbank herstellen
$mysqli = new mysqli('localhost', 'root', '', 'test');
if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

// ID aus der URL abrufen
$id = $_GET['id'] ?? null;

if ($id) {
    // Eintrag löschen
    $stmt = $mysqli->prepare("DELETE FROM reports WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Eintrag erfolgreich gelöscht.";
    } else {
        echo "Fehler beim Löschen des Eintrags: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Keine gültige ID angegeben.";
}

$mysqli->close();
?>
<a href="dashboard.php" class="btn btn-primary">Zurück zum Dashboard</a>
