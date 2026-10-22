<?php
$pageTitle = "Manage Events";
$activeNav = "manage_events";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/logger.php";

require_admin();

$msg = "";
$msgType = "success";

// Handle Event Delete
if (isset($_GET["action"]) && $_GET["action"] === "delete") {
    $delId = intval($_GET["id"] ?? 0);
    if ($delId > 0) {
        $pRes = mysqli_query($conn, "SELECT poster, title FROM events WHERE id = $delId LIMIT 1");
        if ($pRes && mysqli_num_rows($pRes) > 0) {
            $pRow = mysqli_fetch_assoc($pRes);
            if (!empty($pRow["poster"]) && file_exists(__DIR__ . "/uploads/" . $pRow["poster"])) {
                unlink(__DIR__ . "/uploads/" . $pRow["poster"]);
            }
            mysqli_query($conn, "DELETE FROM events WHERE id = $delId");
            log_system_action($conn, $_SESSION["user_id"], "EVENT_DELETE", "Deleted event #$delId (" . $pRow["title"] . ")");
            $msg = "Event deleted successfully.";
        }
    }
}

// Handle Add Event
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title       = trim(mysqli_real_escape_string($conn, $_POST["title"] ?? ""));
    $description = trim(mysqli_real_escape_string($conn, $_POST["description"] ?? ""));
    $category    = trim(mysqli_real_escape_string($conn, $_POST["category"] ?? ""));
    $eventDate   = trim(mysqli_real_escape_string($conn, $_POST["event_date"] ?? ""));
    $venue       = trim(mysqli_real_escape_string($conn, $_POST["venue"] ?? ""));
    $maxSeats    = intval($_POST["max_seats"] ?? 50);
    $posterName  = null;

    if (empty($title) || empty($category) || empty($eventDate) || empty($venue)) {
        $msg = "All text fields are required.";
        $msgType = "danger";
    } else {
        // Handle file upload
        if (isset($_FILES["poster"]) && $_FILES["poster"]["error"] === UPLOAD_ERR_OK) {
            $file = $_FILES["poster"];
            $ext  = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
            $allowed = ["jpg", "jpeg", "png", "webp"];

            if ($file["size"] > 2 * 1024 * 1024) {
                $msg = "Poster file size exceeds 2MB limit.";
                $msgType = "danger";
            } elseif (!in_array($ext, $allowed)) {
                $msg = "Only JPG, PNG, and WEBP formats allowed.";
                $msgType = "danger";
            } else {
                $posterName = "poster_" . time() . "_" . bin2hex(random_bytes(3)) . "." . $ext;
                $targetDir  = __DIR__ . "/uploads";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                move_uploaded_file($file["tmp_name"], $targetDir . "/" . $posterName);
            }
        }

        if ($msgType !== "danger") {
            $posterSqlVal = $posterName ? "'$posterName'" : "NULL";
            $insSql = "INSERT INTO events (title, description, category, event_date, venue, max_seats, poster) 
                       VALUES ('$title', '$description', '$category', '$eventDate', '$venue', $maxSeats, $posterSqlVal)";
            if (mysqli_query($conn, $insSql)) {
                $newEid = mysqli_insert_id($conn);
                log_system_action($conn, $_SESSION["user_id"], "EVENT_CREATE", "Created event #$newEid ($title)");
                $msg = "New event published successfully!";
                $msgType = "success";
            }
        }
    }
}

$eventsRes = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
?>

<div class="card">
  <h2>Campus Events Administration</h2>

  <?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msgType; ?>"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <!-- Add Event Form Accordion -->
  <details style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
    <summary style="font-weight: bold; cursor: pointer; color: #003366;">➕ Publish New Event Announcement</summary>
    <form action="manage_events.php" method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
      <div class="grid-2col">
        <div class="form-group">
          <label>Event Title: *</label>
          <input type="text" name="title" required placeholder="e.g. Flutter Mobile Bootcamp">
        </div>
        <div class="form-group">
          <label>Category: *</label>
          <select name="category" required>
            <option value="Technical">Technical</option>
            <option value="Workshop">Workshop</option>
            <option value="Cultural">Cultural</option>
            <option value="Sports">Sports</option>
          </select>
        </div>
      </div>
      <div class="grid-3col">
        <div class="form-group">
          <label>Event Date: *</label>
          <input type="date" name="event_date" required>
        </div>
        <div class="form-group">
          <label>Venue: *</label>
          <input type="text" name="venue" required placeholder="e.g. FTE Lab 1">
        </div>
        <div class="form-group">
          <label>Max Seats: *</label>
          <input type="number" name="max_seats" value="60" required>
        </div>
      </div>
      <div class="form-group">
        <label>Description: *</label>
        <textarea name="description" rows="2" required placeholder="Event agenda, requirements..."></textarea>
      </div>
      <div class="form-group">
        <label>Poster Image (Optional, max 2MB):</label>
        <input type="file" name="poster" accept=".jpg,.jpeg,.png,.webp">
      </div>
      <button type="submit" class="btn btn-success">Publish Event</button>
    </form>
  </details>

  <!-- Events List Table -->
  <table>
    <thead>
      <tr>
        <th style="width: 70px;">Poster</th>
        <th>Event Name</th>
        <th>Category</th>
        <th>Date</th>
        <th>Venue</th>
        <th>Capacity</th>
        <th style="text-align: center;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($eventsRes && mysqli_num_rows($eventsRes) > 0): ?>
        <?php while ($evt = mysqli_fetch_assoc($eventsRes)): ?>
          <tr>
            <td>
              <?php if (!empty($evt["poster"]) && file_exists(__DIR__ . "/uploads/" . $evt["poster"])): ?>
                <img src="uploads/<?php echo htmlspecialchars($evt["poster"]); ?>" alt="Poster" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
              <?php else: ?>
                <div style="width: 50px; height: 50px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #64748b;">No img</div>
              <?php endif; ?>
            </td>
            <td><strong><?php echo htmlspecialchars($evt["title"]); ?></strong></td>
            <td><span style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold;"><?php echo htmlspecialchars($evt["category"]); ?></span></td>
            <td><?php echo date("d-M-Y", strtotime($evt["event_date"])); ?></td>
            <td><?php echo htmlspecialchars($evt["venue"]); ?></td>
            <td><?php echo htmlspecialchars($evt["max_seats"]); ?></td>
            <td style="text-align: center; white-space: nowrap;">
              <a href="manage_events.php?action=delete&id=<?php echo $evt["id"]; ?>" class="btn btn-danger" style="padding: 3px 8px; font-size: 11px;" onclick="return confirm('Delete event <?php echo htmlspecialchars($evt["title"]); ?>?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align: center;">No events listed yet.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
