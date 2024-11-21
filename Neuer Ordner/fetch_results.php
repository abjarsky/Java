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

// SQL-Abfrage
$result = $mysqli->query("
    SELECT 
        id, firstname, lastname, address, zipcode, city, project, date, start_time, end_time, pause_time, total_time, signature 
    FROM reports
");

if ($result->num_rows > 0): ?>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Adresse</th>
                <th>PLZ</th>
                <th>Stadt</th>
                <th>Projekt</th>
                <th>Datum</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Pause</th>
                <th>Gesamtzeit</th>
                <th>Signatur</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['id']) ?></td>
                    <td><?= htmlspecialchars($row['firstname']) ?></td>
                    <td><?= htmlspecialchars($row['lastname']) ?></td>
                    <td><?= htmlspecialchars($row['address']) ?></td>
                    <td><?= htmlspecialchars($row['zipcode']) ?></td>
                    <td><?= htmlspecialchars($row['city']) ?></td>
                    <td><?= htmlspecialchars($row['project']) ?></td>
                    <td><?= htmlspecialchars($row['date']) ?></td>
                    <td><?= htmlspecialchars($row['start_time']) ?></td>
                    <td><?= htmlspecialchars($row['end_time']) ?></td>
                    <td><?= htmlspecialchars($row['pause_time']) ?></td>
                    <td><?= htmlspecialchars($row['total_time']) ?> Minuten</td>
                    <td><img src="<?= htmlspecialchars($row['signature']) ?>" alt="Signatur"></td>
                    <td>
                        <a href="update.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-primary">Bearbeiten</a>
                        <a href="delete.php?id=<?= htmlspecialchars($row['id']) ?>" class="btn btn-danger">Löschen</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Keine Ergebnisse gefunden</p>
<?php endif;

$mysqli->close();
?>
