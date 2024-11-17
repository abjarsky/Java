<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit();
}

$mysqli = new mysqli('localhost', 'root', '', 'test');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Properly formatted SQL query with SQL-style comments
$result = $mysqli->query("
    SELECT 
        reports.customer_id, 
        reports.date, 
        locations.name AS location_name, 
        projects.name AS project_name, 
        reports.signature_path,
        reports.total_time -- Added total_time to the SELECT clause
    FROM 
        reports
    JOIN customers ON reports.customer_id = customers.customer_id
    JOIN locations ON reports.location_id = locations.location_id
    JOIN projects ON reports.project_id = projects.project_id");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['customer_id']}</td>
                <td>{$row['date']}</td>
                <td>{$row['location_name']}</td>
                <td>{$row['project_name']}</td>
                <td><img src='{$row['signature_path']}' alt='Signature' style='width: 100px;'/></td>
                <td>{$row['total_time']} Stunden</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>Keine Daten gefunden</td></tr>";
}
$mysqli->close();
?>
