<?php
// Practical 13: Database Connection for JSON API
$conn = mysqli_connect("localhost", "root", "", "studenthub_db");
if (!$conn) {
    header("Content-Type: application/json");
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}
mysqli_set_charset($conn, "utf8mb4");
?>
