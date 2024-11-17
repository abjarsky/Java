<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

// Database connection setup
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';
$mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);
if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve input data
    $customer_id = $mysqli->real_escape_string($_POST['customer']);
    $date = $mysqli->real_escape_string($_POST['date']);
    $location_id = $mysqli->real_escape_string($_POST['location']);
    $project_id = $mysqli->real_escape_string($_POST['project']);
    $signature = $_POST['signature']; // This should be a base64 encoded image

    // Calculating total time
    $start_time = new DateTime($_POST['start_time']);
    $end_time = new DateTime($_POST['end_time']);
    $pause_time = intval($_POST['pause_time']); // in minutes
    $totalInterval = $end_time->diff($start_time);
    $totalHours = $totalInterval->h + ($totalInterval->i / 60) - ($pause_time / 60);
    $total_time = number_format($totalHours, 2, '.', '');

    // Path to save the signature image
    $uploadPath = 'C:/xampp/htdocs/checkin/upload/';
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }
    $signatureFilename = $uploadPath . uniqid() . '_signature.png';

    // Decode and save the signature image
    if (!empty($signature)) {
        $signatureData = explode(',', $signature)[1]; // Remove the base64 header
        $signatureDecoded = base64_decode($signatureData);
        file_put_contents($signatureFilename, $signatureDecoded);
    }

    // Insert form data into the database
    $stmt = $mysqli->prepare("INSERT INTO reports (customer_id, date, location_id, project_id, signature_path, total_time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ississ", $customer_id, $date, $location_id, $project_id, $signatureFilename, $total_time);
    if ($stmt->execute()) {
        echo "Daten erfolgreich gespeichert.<br>";
        echo "Kunde: $customer_id<br>Datum: $date<br>Standort: $location_id<br>Projekt: $project_id<br>Signatur gespeichert unter: $signatureFilename<br>Gesamtarbeitszeit: $total_time Stunden";
    } else {
        echo "Fehler beim Speichern der Daten: " . $stmt->error;
    }
    $stmt->close();
    echo '<p><a href="dashboard.php" class="btn btn-primary">Zurück zum Dashboard</a></p>'; // Button to go back to the dashboard
} else {
    echo "Keine Daten übermittelt.";
    echo '<p><a href="dashboard.php" class="btn btn-primary">Zurück zum Dashboard</a></p>'; // Button to go back to the dashboard if no data is posted
}
$mysqli->close();
?>
