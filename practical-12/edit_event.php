<?php
// Practical 12: Edit Event Record & Replace Poster
require_once __DIR__ . "/db.php";

$id = intval($_GET["id"] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

$res = mysqli_query($conn, "SELECT * FROM events WHERE id = $id LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    header("Location: index.php?msg=error");
    exit;
}
$event = mysqli_fetch_assoc($res);
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim(mysqli_real_escape_string($conn, $_POST["title"] ?? ""));
    $description = trim(mysqli_real_escape_string($conn, $_POST["description"] ?? ""));
    $category    = trim(mysqli_real_escape_string($conn, $_POST["category"] ?? ""));
    $eventDate   = trim(mysqli_real_escape_string($conn, $_POST["event_date"] ?? ""));
    $venue       = trim(mysqli_real_escape_string($conn, $_POST["venue"] ?? ""));
    $maxSeats    = intval($_POST["max_seats"] ?? 50);
    $posterFilename = $event["poster"]; // Keep existing by default

    // Validate text inputs
    if (empty($title) || empty($description) || empty($category) || empty($eventDate) || empty($venue)) {
        $error = "All text fields are required.";
    }

    // Check if a new poster file was uploaded
    if (empty($error) && isset($_FILES["poster"]) && $_FILES["poster"]["error"] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES["poster"];

        if ($file["error"] !== UPLOAD_ERR_OK) {
            $error = "File upload failed with error code: " . $file["error"];
        } else {
            $maxFileSize = 2 * 1024 * 1024;
            $allowedExtensions = ["jpg", "jpeg", "png", "webp"];
            $allowedMimeTypes  = ["image/jpeg", "image/png", "image/webp"];

            $fileSize = $file["size"];
            $fileTmp  = $file["tmp_name"];
            $fileName = $file["name"];
            $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if ($fileSize > $maxFileSize) {
                $error = "Poster file size exceeds the 2MB limit.";
            } elseif (!in_array($fileExt, $allowedExtensions)) {
                $error = "Invalid file extension. Only JPG, PNG, and WEBP images are allowed.";
            } elseif (!in_array(mime_content_type($fileTmp), $allowedMimeTypes)) {
                $error = "Security Warning: Invalid MIME type.";
            } else {
                $newFilename = "poster_" . time() . "_" . bin2hex(random_bytes(4)) . "." . $fileExt;
                $targetDir = __DIR__ . "/uploads";
                $targetPath = $targetDir . "/" . $newFilename;

                if (move_uploaded_file($fileTmp, $targetPath)) {
                    // Remove old poster file if present
                    if (!empty($event["poster"]) && file_exists($targetDir . "/" . $event["poster"])) {
                        unlink($targetDir . "/" . $event["poster"]);
                    }
                    $posterFilename = $newFilename;
                } else {
                    $error = "Failed to store new poster image.";
                }
            }
        }
    }

    // Update MySQL Database
    if (empty($error)) {
        $posterCol = $posterFilename ? "'$posterFilename'" : "NULL";
        $updateSql = "UPDATE events SET 
                        title = '$title',
                        description = '$description',
                        category = '$category',
                        event_date = '$eventDate',
                        venue = '$venue',
                        max_seats = $maxSeats,
                        poster = $posterCol
                      WHERE id = $id";

        if (mysqli_query($conn, $updateSql)) {
            header("Location: index.php?msg=updated");
            exit;
        } else {
            $error = "Update error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Campus Event</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container" style="max-width: 650px;">
    <header>
      <h1>Edit Event #<?php echo $id; ?></h1>
      <a href="index.php" class="btn btn-primary">&larr; Back to Events</a>
    </header>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="edit_event.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="form-box">
      
      <div class="form-group">
        <label for="title">Event Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event["title"]); ?>" required>
      </div>

      <div class="form-group">
        <label for="category">Category:</label>
        <select id="category" name="category" required>
          <?php $cats = ["Technical", "Workshop", "Cultural", "Sports", "Seminar"]; ?>
          <?php foreach ($cats as $c): ?>
            <option value="<?php echo $c; ?>" <?php if ($event["category"] === $c) echo "selected"; ?>><?php echo $c; ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="event_date">Date of Event:</label>
        <input type="date" id="event_date" name="event_date" value="<?php echo htmlspecialchars($event["event_date"]); ?>" required>
      </div>

      <div class="form-group">
        <label for="venue">Event Venue:</label>
        <input type="text" id="venue" name="venue" value="<?php echo htmlspecialchars($event["venue"]); ?>" required>
      </div>

      <div class="form-group">
        <label for="max_seats">Maximum Seats:</label>
        <input type="number" id="max_seats" name="max_seats" value="<?php echo htmlspecialchars($event["max_seats"]); ?>" required>
      </div>

      <div class="form-group">
        <label for="description">Event Description:</label>
        <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($event["description"]); ?></textarea>
      </div>

      <div class="form-group">
        <label>Current Poster Image:</label>
        <?php if (!empty($event["poster"]) && file_exists(__DIR__ . "/uploads/" . $event["poster"])): ?>
          <div style="display: flex; align-items: center; gap: 15px; margin: 8px 0;">
            <img src="uploads/<?php echo htmlspecialchars($event["poster"]); ?>" alt="Current Poster" class="poster-thumb">
            <span style="font-size: 13px; color: #64748b;">File: <?php echo htmlspecialchars($event["poster"]); ?></span>
          </div>
        <?php else: ?>
          <p style="font-size: 13px; color: #64748b; font-style: italic;">No poster currently attached.</p>
        <?php endif; ?>

        <label for="poster" style="margin-top: 10px;">Replace Poster (Optional, max 2MB):</label>
        <input type="file" id="poster" name="poster" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <button type="submit" class="btn btn-warning" style="width: 100%; padding: 10px; font-size: 15px;">Save Changes</button>
    </form>
  </div>

</body>
</html>
