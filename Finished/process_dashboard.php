<?php
session_start();

// Database connection setup
$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';
$mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Optional: Retrieve additional form data
    $customer_id = isset($_POST['customer']) ? $mysqli->real_escape_string($_POST['customer']) : null;
    $date = isset($_POST['date']) ? $mysqli->real_escape_string($_POST['date']) : null;
    $location = isset($_POST['location']) ? $mysqli->real_escape_string($_POST['location']) : null;
    $project = isset($_POST['project']) ? $mysqli->real_escape_string($_POST['project']) : null;
    $signature = isset($_POST['signature']) ? $_POST['signature'] : '';

    // Process and save the signature if it exists
    if (!empty($signature)) {
        $encoded_image = explode(",", $signature)[1];
        $decoded_image = base64_decode($encoded_image);

        // Save the PNG binary to a file
        $filename = 'signatures/signature_' . time() . '.png';  // Ensure unique filename
        file_put_contents($filename, $decoded_image);

        // Optionally, save to database (as a path or BLOB)
        $stmt = $mysqli->prepare("INSERT INTO signatures (customer_id, file_path, date, location_id, project_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issii", $customer_id, $filename, $date, $location, $project);
        $stmt->execute();
        $stmt->close();
    }

    // Additional form processing logic here

    // Redirect to dashboard with a success message
    header("Location: dashboard.php?status=success");
    exit;
} else {
    // Redirect back or show an error if the form wasn't submitted correctly
    header("Location: dashboard.php?status=error");
    exit;
}
?>
