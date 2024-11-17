<?php
session_start();

$host = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'test';

// Create connection
$mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $mysqli->real_escape_string($_POST['user-id']);
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $mysqli->real_escape_string($_POST['password']); // Consider using password_hash in real applications

    // Check if user ID already exists
    $checkUser = $mysqli->query("SELECT * FROM users WHERE id='$userId'");
    if ($checkUser->num_rows > 0) {
        echo "Benutzer-ID existiert bereits. Bitte eine andere ID wählen.";
    } else {
        // Insert new user
        $sql = "INSERT INTO users (id, username, password) VALUES ('$userId', '$username', '$password')";
        if ($mysqli->query($sql) === TRUE) {
            echo "Neuer Benutzer erfolgreich registriert!";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
    }
    $mysqli->close();
}
?>
