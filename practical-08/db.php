<?php
// Practical 8: Database Connection File using MySQLi
// StudentHub Portal Database Configuration

$db_host = "localhost";
$db_user = "root";       // Default XAMPP MySQL user
$db_pass = "";           // Default XAMPP MySQL password (empty)
$db_name = "studenthub_db";

// Establish connection to MySQL server
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check if connection was successful
if (!$conn) {
    die("❌ Database connection failed: " . mysqli_connect_error());
}

// Set character set to utf8mb4 for unicode compatibility
mysqli_set_charset($conn, "utf8mb4");

// Note: Reusable in all subsequent practicals via:
// require_once __DIR__ . '/db.php';
?>
