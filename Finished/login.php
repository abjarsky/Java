<?php
session_start();
$host = 'localhost'; // or your host
$dbUsername = 'root'; // your database username
$dbPassword = ''; // your database password
$dbName = 'test'; // your database name

$mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $mysqli->real_escape_string($_POST['user-id']);
    $password = $mysqli->real_escape_string($_POST['password']);

    $query = "SELECT * FROM users WHERE id='$userId' AND password='$password'";
    $result = $mysqli->query($query);

    if ($result->num_rows == 1) {
        $_SESSION['user_id'] = $userId;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid login credentials.";
    }
}
$mysqli->close();
?>
