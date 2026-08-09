<?php
// Database credentials
$host = "localhost";
$user = "root";
$pass = "";
$db   = "prisons";

// Create connection using MySQLi
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// If you want to confirm connection
// echo "Connected successfully!";
?>
