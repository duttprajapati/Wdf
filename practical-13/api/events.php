<?php
// Practical 13: PHP RESTful JSON API for Events
header("Content-Type: application/json; charset=UTF-8");
require_once __DIR__ . "/../db.php";

$method = $_SERVER["REQUEST_METHOD"];

// Helper function to send JSON responses
function sendResponse($success, $message, $data = null) {
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data"    => $data
    ]);
    exit;
}

// -------------------------------------------------------------
// 1. GET Request: Fetch all events or a single event by ID
// -------------------------------------------------------------
if ($method === "GET") {
    $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

    if ($id > 0) {
        $res = mysqli_query($conn, "SELECT * FROM events WHERE id = $id LIMIT 1");
        if ($res && mysqli_num_rows($res) > 0) {
            sendResponse(true, "Event found", mysqli_fetch_assoc($res));
        } else {
            sendResponse(false, "Event not found with ID: $id");
        }
    } else {
        $res = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
        $events = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $events[] = $row;
        }
        sendResponse(true, "Events list retrieved", $events);
    }
}

// Parse request payload (supports JSON payloads & standard form data)
$inputJSON = file_get_contents("php://input");
$body = json_decode($inputJSON, true) ?? $_POST;
$action = $body["action"] ?? ($_GET["action"] ?? "");

// -------------------------------------------------------------
// 2. DELETE Operation: Remove an event
// -------------------------------------------------------------
if ($method === "DELETE" || ($method === "POST" && $action === "delete")) {
    $id = intval($body["id"] ?? ($_GET["id"] ?? 0));

    if ($id <= 0) {
        sendResponse(false, "Invalid or missing Event ID for deletion");
    }

    $delSql = "DELETE FROM events WHERE id = $id";
    if (mysqli_query($conn, $delSql)) {
        sendResponse(true, "Event #$id was deleted successfully");
    } else {
        sendResponse(false, "Delete query failed: " . mysqli_error($conn));
    }
}

// -------------------------------------------------------------
// 3. UPDATE Operation: Modify existing event
// -------------------------------------------------------------
if ($method === "PUT" || ($method === "POST" && $action === "update")) {
    $id          = intval($body["id"] ?? 0);
    $title       = trim(mysqli_real_escape_string($conn, $body["title"] ?? ""));
    $description = trim(mysqli_real_escape_string($conn, $body["description"] ?? ""));
    $category    = trim(mysqli_real_escape_string($conn, $body["category"] ?? ""));
    $eventDate   = trim(mysqli_real_escape_string($conn, $body["event_date"] ?? ""));
    $venue       = trim(mysqli_real_escape_string($conn, $body["venue"] ?? ""));
    $maxSeats    = intval($body["max_seats"] ?? 50);

    if ($id <= 0 || empty($title) || empty($category) || empty($eventDate) || empty($venue)) {
        sendResponse(false, "Validation error: Missing required fields");
    }

    $updateSql = "UPDATE events SET 
                    title = '$title',
                    description = '$description',
                    category = '$category',
                    event_date = '$eventDate',
                    venue = '$venue',
                    max_seats = $maxSeats
                  WHERE id = $id";

    if (mysqli_query($conn, $updateSql)) {
        sendResponse(true, "Event #$id updated successfully");
    } else {
        sendResponse(false, "Update failed: " . mysqli_error($conn));
    }
}

// -------------------------------------------------------------
// 4. POST Request: Create a new event
// -------------------------------------------------------------
if ($method === "POST") {
    $title       = trim(mysqli_real_escape_string($conn, $body["title"] ?? ""));
    $description = trim(mysqli_real_escape_string($conn, $body["description"] ?? ""));
    $category    = trim(mysqli_real_escape_string($conn, $body["category"] ?? ""));
    $eventDate   = trim(mysqli_real_escape_string($conn, $body["event_date"] ?? ""));
    $venue       = trim(mysqli_real_escape_string($conn, $body["venue"] ?? ""));
    $maxSeats    = intval($body["max_seats"] ?? 50);

    if (empty($title) || empty($description) || empty($category) || empty($eventDate) || empty($venue)) {
        sendResponse(false, "Validation error: All event fields are required");
    }

    $insertSql = "INSERT INTO events (title, description, category, event_date, venue, max_seats) 
                  VALUES ('$title', '$description', '$category', '$eventDate', '$venue', $maxSeats)";

    if (mysqli_query($conn, $insertSql)) {
        $newId = mysqli_insert_id($conn);
        sendResponse(true, "New event created successfully", ["id" => $newId, "title" => $title]);
    } else {
        sendResponse(false, "Failed to insert event: " . mysqli_error($conn));
    }
}

// Fallback for unsupported HTTP verbs
sendResponse(false, "Unsupported HTTP Request Method");
?>
