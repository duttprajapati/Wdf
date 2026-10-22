<?php
// Practical 15: Unified JSON API Endpoint
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/includes/db.php";

$res = mysqli_query($conn, "SELECT id, title, category, event_date, venue, max_seats FROM events ORDER BY event_date ASC");
$events = [];

if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $events[] = $row;
    }
}

echo json_encode(["status" => "success", "count" => count($events), "data" => $events]);
exit;
?>
