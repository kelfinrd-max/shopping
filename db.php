<?php
// db.php — Database connection file

// ✅ Start session safely (avoids duplicate session_start() warning)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "smart_cart";

$conn = new mysqli($servername, $username, $password, $database);

// ✅ Connection error handling
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set character set
$conn->set_charset("utf8mb4");
?>
