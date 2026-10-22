<?php
// Practical 12: Add Event with Poster File Upload & Validation
require_once __DIR__ . "/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim(mysqli_real_escape_string($conn, $_POST["title"] ?? ""));
    $description = trim(mysqli_real_escape_string($conn, $_POST["description"] ?? ""));
    $category    = trim(mysqli_real_escape_string($conn, $_POST["category"] ?? ""));
    $eventDate   = trim(mysqli_real_escape_string($conn, $_POST["event_date"] ?? ""));
    $venue       = trim(mysqli_real_escape_string($conn, $_POST["venue"] ?? ""));
    $maxSeats    = intval($_POST["max_seats"] ?? 50);
    $posterFilename = null;

    // 1. Text Field Validations
    if (empty($title) || empty($description) || empty($category) || empty($eventDate) || empty($venue)) {
        $error = "All text fields are required.";
    }

    // 2. Poster File Upload Validation
    if (empty($error) && isset($_FILES["poster"]) && $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["poster"];

        // Check for upload errors
        if ($file["error"] !== UPLOAD_ERR_OK) {
            $error = "File upload failed with error code: " . $file["error"];
        } else {
            $maxFileSize = 2 * 1024 * 1024; // 2 Megabytes
            $allowedExtensions = ["jpg", "jpeg", "png", "webp"];
            $allowedMimeTypes  = ["image/jpeg", "image/png", "image/webp"];

            $fileSize = $file["size"];
            $fileTmp  = $file["tmp_name"];
            $fileName = $file["name"];
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validate File Size
            if ($fileSize > $maxFileSize) {
                $error = "Poster file size exceeds the 2MB limit. Uploaded size: " . round($fileSize / 1024 / 1024, 2) . "MB.";
            }
            // Validate File Extension
            elseif (!in_array($fileExt, $allowedExtensions)) {
                $error = "Invalid image type. Only JPG, PNG, and WEBP images are allowed.";
            }
            // Validate MIME type
            elseif (!in_array(mime_content_type($fileTmp), $allowedMimeTypes)) {
                $error = "Security Warning: Uploaded file is not a valid image.";
            } else {
                // Generate secure sanitized unique file name
                $posterFilename = "poster_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $fileExt;
                $targetDir = __DIR__ . "/uploads";

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $targetPath = $targetDir . "/" . $posterFilename;
                if (!move_uploaded_file($fileTmp, $targetPath)) {
                    $error = "Failed to move uploaded poster to storage directory.";
                }
            }
        }
    }

    // 3. Insert Record into MySQL
    if (empty($error)) {
        $posterColVal = $posterFilename ? "'$posterFilename'" : "NULL";
        $insertSql = "INSERT INTO events (title, description, category, event_date, venue, max_seats, poster) 
                      VALUES ('$title', '$description', '$category', '$eventDate', '$venue', $maxSeats, $posterColVal)";

        if (mysqli_query($conn, $insertSql)) {
            header("Location: index.php?msg=added");
            exit;
        } else {
            $error = "Database insert error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Campus Event</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container" style="max-width: 650px;">
    <header>
      <h1>Create New Campus Event</h1>
      <a href="index.php" class="btn btn-primary">&larr; Back to Events</a>
    </header>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- Must include enctype="multipart/form-data" for file upload -->
    <form action="add_event.php" method="POST" enctype="multipart/form-data" class="form-box">
      
      <div class="form-group">
        <label for="title">Event Title: *</label>
        <input type="text" id="title" name="title" placeholder="e.g. AI & Robotics Symposium" required>
      </div>

      <div class="form-group">
        <label for="category">Category: *</label>
        <select id="category" name="category" required>
          <option value="Technical">Technical</option>
          <option value="Workshop">Workshop</option>
          <option value="Cultural">Cultural</option>
          <option value="Sports">Sports</option>
          <option value="Seminar">Seminar</option>
        </select>
      </div>

      <div class="form-group">
        <label for="event_date">Date of Event: *</label>
        <input type="date" id="event_date" name="event_date" required>
      </div>

      <div class="form-group">
        <label for="venue">Event Venue: *</label>
        <input type="text" id="venue" name="venue" placeholder="e.g. FTE Seminar Hall" required>
      </div>

      <div class="form-group">
        <label for="max_seats">Maximum Participant Capacity: *</label>
        <input type="number" id="max_seats" name="max_seats" value="100" min="1" required>
      </div>

      <div class="form-group">
        <label for="description">Event Description: *</label>
        <textarea id="description" name="description" rows="3" placeholder="Provide schedule, speakers, or topics..." required></textarea>
      </div>

      <div class="form-group">
        <label for="poster">Upload Event Poster (Max 2MB - JPG, PNG, WEBP):</label>
        <input type="file" id="poster" name="poster" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <button type="submit" class="btn btn-success" style="width: 100%; padding: 10px; font-size: 15px;">Create &amp; Publish Event</button>
    </form>
  </div>

</body>
</html>
