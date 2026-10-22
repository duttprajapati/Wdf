<?php
// Practical 12: Event Management CRUD - View All Events
require_once __DIR__ . "/db.php";

$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = mysqli_query($conn, $sql);
$eventCount = $result ? mysqli_num_rows($result) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - Event Management CRUD (Practical 12)</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container">
    <header>
      <div>
        <h1>Campus Event Management</h1>
        <p style="color: #64748b; font-size: 13px;">Practical 12: Event CRUD with Poster Upload &amp; File Validation</p>
      </div>
      <div>
        <a href="add_event.php" class="btn btn-success">➕ Create New Event</a>
      </div>
    </header>

    <!-- Feedback Flash Messages -->
    <?php if (isset($_GET["msg"])): ?>
      <?php if ($_GET["msg"] === "added"): ?>
        <div class="alert alert-success">✅ Event created successfully with poster upload!</div>
      <?php elseif ($_GET["msg"] === "updated"): ?>
        <div class="alert alert-success">✅ Event details and poster updated successfully!</div>
      <?php elseif ($_GET["msg"] === "deleted"): ?>
        <div class="alert alert-success">✅ Event and associated poster image deleted successfully!</div>
      <?php elseif ($_GET["msg"] === "error"): ?>
        <div class="alert alert-danger">❌ An error occurred during database processing.</div>
      <?php endif; ?>
    <?php endif; ?>

    <p style="font-size: 13px; color: #64748b; margin-bottom: 10px;">
      Total Events Scheduled: <strong><?php echo $eventCount; ?></strong>
    </p>

    <?php if ($eventCount > 0): ?>
      <table>
        <thead>
          <tr>
            <th style="width: 80px;">Poster</th>
            <th>Event Title &amp; Details</th>
            <th>Category</th>
            <th>Date</th>
            <th>Venue</th>
            <th>Seats</th>
            <th style="text-align: center; width: 140px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td>
                <?php if (!empty($row["poster"]) && file_exists(__DIR__ . "/uploads/" . $row["poster"])): ?>
                  <img src="uploads/<?php echo htmlspecialchars($row["poster"]); ?>" alt="Event Poster" class="poster-thumb">
                <?php else: ?>
                  <div class="no-poster">No Poster</div>
                <?php endif; ?>
              </td>
              <td>
                <strong style="color: #003366;"><?php echo htmlspecialchars($row["title"]); ?></strong>
                <p style="font-size: 12px; color: #64748b; margin-top: 4px;"><?php echo htmlspecialchars(substr($row["description"], 0, 75)) . "..."; ?></p>
              </td>
              <td><span style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: bold;"><?php echo htmlspecialchars($row["category"]); ?></span></td>
              <td><?php echo date("d-M-Y", strtotime($row["event_date"])); ?></td>
              <td><?php echo htmlspecialchars($row["venue"]); ?></td>
              <td><?php echo htmlspecialchars($row["max_seats"]); ?></td>
              <td style="text-align: center; white-space: nowrap;">
                <a href="edit_event.php?id=<?php echo $row["id"]; ?>" class="btn btn-warning">Edit</a>
                <a href="delete_event.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger" onclick="return confirm('Delete this event and its poster file?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div style="text-align: center; padding: 40px; color: #64748b;">
        <p>No events found. Click "Create New Event" to add your first event.</p>
      </div>
    <?php endif; ?>

  </div>

</body>
</html>
