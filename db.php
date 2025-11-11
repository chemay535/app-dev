<?php
// Database configuration
$host = "localhost";
$user = "root"; // Default XAMPP user
$pass = ""; // Default XAMPP password = empty
$db = "eventtrack_db";

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Optional: Set proper character encoding
$conn->set_charset("utf8");

?>
