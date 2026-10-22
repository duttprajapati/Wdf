<?php
$pageTitle = "Student Dashboard";
$activeNav = "dashboard";
require_once __DIR__ . "/includes/header.php";

// Guard: Only students can access
require_student();

$userId = intval($_SESSION["user_id"]);

// Fetch student profile details
$stSql = "SELECT * FROM students WHERE user_id = $userId LIMIT 1";
$stRes = mysqli_query($conn, $stSql);
$student = ($stRes && mysqli_num_rows($stRes) > 0) ? mysqli_fetch_assoc($stRes) : null;
$studentId = $student["id"] ?? 0;

// Fetch registered events for this student
$regsSql = "SELECT r.id, r.registration_date, r.status, e.title, e.category, e.event_date, e.venue 
            FROM registrations r
            JOIN events e ON r.event_id = e.id
            WHERE r.student_id = $studentId
            ORDER BY e.event_date ASC";
$regsRes = mysqli_query($conn, $regsSql);
?>

<div class="metrics-row">
  <div class="metric-box">
    <h4>Enrolled Events</h4>
    <div class="val"><?php echo $regsRes ? mysqli_num_rows($regsRes) : 0; ?></div>
  </div>
  <div class="metric-box" style="border-left-color: #10b981;">
    <h4>Academic Department</h4>
    <div class="val" style="font-size: 18px;"><?php echo htmlspecialchars($student["course"] ?? "N/A"); ?></div>
  </div>
  <div class="metric-box" style="border-left-color: #f59e0b;">
    <h4>Enrollment No.</h4>
    <div class="val" style="font-size: 18px;"><?php echo htmlspecialchars($student["enrollment_no"] ?? "N/A"); ?></div>
  </div>
</div>

<div class="grid-2col">
  <!-- Profile Summary -->
  <div class="card">
    <h2>Student Academic Profile</h2>
    <table style="border: none;">
      <tr><td style="font-weight: bold; width: 140px; border:none; padding: 6px 0;">Full Name:</td><td style="border:none; padding: 6px 0;"><?php echo htmlspecialchars($student["full_name"] ?? $userName); ?></td></tr>
      <tr><td style="font-weight: bold; border:none; padding: 6px 0;">Enrollment:</td><td style="border:none; padding: 6px 0;"><?php echo htmlspecialchars($student["enrollment_no"] ?? "N/A"); ?></td></tr>
      <tr><td style="font-weight: bold; border:none; padding: 6px 0;">College Email:</td><td style="border:none; padding: 6px 0;"><?php echo htmlspecialchars($student["email"] ?? "N/A"); ?></td></tr>
      <tr><td style="font-weight: bold; border:none; padding: 6px 0;">Mobile:</td><td style="border:none; padding: 6px 0;"><?php echo htmlspecialchars($student["mobile"] ?? "N/A"); ?></td></tr>
      <tr><td style="font-weight: bold; border:none; padding: 6px 0;">Course:</td><td style="border:none; padding: 6px 0;"><?php echo htmlspecialchars($student["course"] ?? "N/A"); ?></td></tr>
      <tr><td style="font-weight: bold; border:none; padding: 6px 0;">Current Semester:</td><td style="border:none; padding: 6px 0;"><?php echo htmlspecialchars($student["semester"] ?? "N/A"); ?>th Sem</td></tr>
    </table>
  </div>

  <!-- Quick Links -->
  <div class="card" style="background: #f8fafc;">
    <h2>Quick Student Actions</h2>
    <p style="font-size: 14px; margin-bottom: 15px;">Discover campus activities and keep track of your schedule.</p>
    <a href="events.php" class="btn btn-primary" style="display: block; text-align: center; margin-bottom: 10px;">📅 Browse Upcoming Events &amp; Register</a>
    <a href="ajax_events.php" class="btn btn-warning" style="display: block; text-align: center;">⚡ Open Live AJAX Explorer</a>
  </div>
</div>

<!-- Registered Events List -->
<div class="card">
  <h2>My Event Registrations</h2>
  <?php if ($regsRes && mysqli_num_rows($regsRes) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Reg ID</th>
          <th>Event Name</th>
          <th>Category</th>
          <th>Event Date</th>
          <th>Venue</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($r = mysqli_fetch_assoc($regsRes)): ?>
          <tr>
            <td>#<?php echo $r["id"]; ?></td>
            <td><strong><?php echo htmlspecialchars($r["title"]); ?></strong></td>
            <td><span style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold;"><?php echo htmlspecialchars($r["category"]); ?></span></td>
            <td><?php echo date("d-M-Y", strtotime($r["event_date"])); ?></td>
            <td><?php echo htmlspecialchars($r["venue"]); ?></td>
            <td><span style="color: #16a34a; font-weight: bold;"><?php echo strtoupper($r["status"]); ?></span></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="color: #64748b; font-style: italic; padding: 10px 0;">You have not registered for any events yet. <a href="events.php" style="color: #0056b3; font-weight: bold;">Browse events now &rarr;</a></p>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
