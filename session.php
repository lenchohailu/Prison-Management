<?php
// Start session safely (prevents double-start warnings)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login status
if (!isset($_SESSION['userName']) || empty($_SESSION['userName'])) {
    header("Location: login.php");
    exit();
}
?>
