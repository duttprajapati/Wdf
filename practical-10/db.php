<?php
// Practical 10: Database Connection
$conn = mysqli_connect("localhost", "root", "", "studenthub_db");
if (!$conn) {
    die("Database connection error: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
?>
