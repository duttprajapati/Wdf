<?php
// Practical 12: Database Connection
$conn = mysqli_connect("localhost", "root", "", "studenthub_db");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>
