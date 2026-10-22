<?php
$pageTitle = "Campus Events";
$activeNav = "events";
require_once __DIR__ . "/includes/header.php";

$message = "";
$messageType = "";

// Handle Student Registration for an Event
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "register") {
    if (!$isLoggedIn) {
        $message = "Please log in with your student account to register for events.";
        $messageType = "danger";
    } elseif ($userRole !== "student") {
        $message = "Only student accounts can register for campus events.";
        $messageType = "danger";
    } else {
        $eventId = intval($_POST["event_id"] ?? 0);
        $userId  = intval($_SESSION["user_id"]);

        // Look up student_id from user_id
        $stRes = mysqli_query($conn, "SELECT id FROM students WHERE user_id = $userId LIMIT 1");
        if ($stRes && mysqli_num_rows($stRes) > 0) {
            $studentId = mysqli_fetch_assoc($stRes)["id"];

            // Check if already registered
            $checkReg = mysqli_query($conn, "SELECT id FROM registrations WHERE student_id = $studentId AND event_id = $eventId LIMIT 1");
            if ($checkReg && mysqli_num_rows($checkReg) > 0) {
                $message = "You have already registered for this event!";
                $messageType = "warning";
            } else {
                $regSql = "INSERT INTO registrations (student_id, event_id, status) VALUES ($studentId, $eventId, 'confirmed')";
                if (mysqli_query($conn, $regSql)) {
                    $message = "Event registration confirmed! You can view it under your Student Dashboard.";
                    $messageType = "success";
                } else {
                    $message = "Registration failed: " . mysqli_error($conn);
                    $messageType = "danger";
                }
            }
        } else {
            $message = "Student profile not found. Please contact administration.";
            $messageType = "danger";
        }
    }
}

// Search and filter parameters
$search = trim($_GET["search"] ?? "");
$category = trim($_GET["category"] ?? "");

$sql = "SELECT * FROM events WHERE 1=1";
if (!empty($search)) {
    $esc = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (title LIKE '%$esc%' OR venue LIKE '%$esc%')";
}
if (!empty($category) && $category !== "ALL") {
    $escCat = mysqli_real_escape_string($conn, $category);
    $sql .= " AND category = '$escCat'";
}
$sql .= " ORDER BY event_date ASC";
$eventsRes = mysqli_query($conn, $sql);
?>

<div class="card">
  <h2>Explore Campus Events &amp; Competitions</h2>
  <p style="color: #64748b; font-size: 14px;">Browse through scheduled academic, cultural, and sporting activities.</p>

  <?php if (!empty($message)): ?>
    <div class="alert alert-<?php echo $messageType; ?>" style="margin-top: 15px;">
      <?php echo htmlspecialchars($message); ?>
    </div>
  <?php endif; ?>

  <!-- Search & Filter Controls -->
  <form action="events.php" method="GET" style="display: flex; gap: 12px; margin-top: 15px; flex-wrap: wrap;">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="🔍 Search event by title or venue..." style="flex: 2; min-width: 200px;">
    
    <select name="category" style="flex: 1; min-width: 150px;">
      <option value="ALL">All Categories</option>
      <option value="Technical" <?php if ($category === "Technical") echo "selected"; ?>>Technical</option>
      <option value="Workshop" <?php if ($category === "Workshop") echo "selected"; ?>>Workshop</option>
      <option value="Cultural" <?php if ($category === "Cultural") echo "selected"; ?>>Cultural</option>
      <option value="Sports" <?php if ($category === "Sports") echo "selected"; ?>>Sports</option>
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="events.php" class="btn" style="background:#94a3b8; color:white;">Reset</a>
  </form>
</div>

<div class="grid-3col">
  <?php if ($eventsRes && mysqli_num_rows($eventsRes) > 0): ?>
    <?php while ($evt = mysqli_fetch_assoc($eventsRes)): ?>
      <article class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <?php if (!empty($evt["poster"]) && file_exists(__DIR__ . "/uploads/" . $evt["poster"])): ?>
            <img src="uploads/<?php echo htmlspecialchars($evt["poster"]); ?>" alt="Poster" style="width: 100%; height: 160px; object-fit: cover; border-radius: 4px; margin-bottom: 12px;">
          <?php endif; ?>

          <span style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
            <?php echo htmlspecialchars($evt["category"]); ?>
          </span>
          <h3 style="margin: 8px 0; color: #003366; font-size: 18px;"><?php echo htmlspecialchars($evt["title"]); ?></h3>
          <p style="font-size: 13px; color: #64748b; margin-bottom: 6px;">
            📅 <strong>Date:</strong> <?php echo date("d-M-Y", strtotime($evt["event_date"])); ?>
          </p>
          <p style="font-size: 13px; color: #64748b; margin-bottom: 6px;">
            📍 <strong>Venue:</strong> <?php echo htmlspecialchars($evt["venue"]); ?>
          </p>
          <p style="font-size: 13px; color: #64748b; margin-bottom: 10px;">
            🪑 <strong>Seats:</strong> <?php echo htmlspecialchars($evt["max_seats"]); ?> capacity
          </p>
          <p style="font-size: 13px; color: #334155; margin-bottom: 15px;">
            <?php echo htmlspecialchars($evt["description"]); ?>
          </p>
        </div>

        <form action="events.php" method="POST">
          <input type="hidden" name="action" value="register">
          <input type="hidden" name="event_id" value="<?php echo $evt["id"]; ?>">
          <button type="submit" class="btn btn-success" style="width: 100%;">Register for Event</button>
        </form>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <p style="color: #64748b;">No events matched your search filter.</p>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
