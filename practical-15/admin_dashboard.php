<?php
$pageTitle = "Admin Dashboard";
$activeNav = "admin";
require_once __DIR__ . "/includes/header.php";

// Guard: Only administrators can access
require_admin();

// Fetch summary metrics
$totStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students"))["c"] ?? 0;
$totEvents   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM events"))["c"] ?? 0;
$totRegs     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM registrations"))["c"] ?? 0;
$totUsers    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))["c"] ?? 0;

// Fetch latest 5 registrations
$latestRegs = mysqli_query($conn, "SELECT r.id, r.registration_date, r.status, s.full_name, s.enrollment_no, e.title 
                                   FROM registrations r
                                   JOIN students s ON r.student_id = s.id
                                   JOIN events e ON r.event_id = e.id
                                   ORDER BY r.id DESC LIMIT 5");

// Fetch latest 5 audit logs
$latestLogs = mysqli_query($conn, "SELECT * FROM audit_logs ORDER BY id DESC LIMIT 5");
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
  <div>
    <h1 style="color: #003366; font-size: 24px;">Executive Administration Dashboard</h1>
    <p style="color: #64748b; font-size: 13px;">Full-stack management overview &bull; Role-Based Authorization Verified</p>
  </div>
  <div style="display: flex; gap: 10px;">
    <a href="manage_students.php" class="btn btn-primary">Manage Students</a>
    <a href="manage_events.php" class="btn btn-warning">Manage Events</a>
  </div>
</div>

<!-- Aggregated Metrics -->
<div class="metrics-row">
  <div class="metric-box" style="border-left-color: #2563eb;">
    <h4>Total Students</h4>
    <div class="val"><?php echo $totStudents; ?></div>
  </div>
  <div class="metric-box" style="border-left-color: #f59e0b;">
    <h4>Published Events</h4>
    <div class="val"><?php echo $totEvents; ?></div>
  </div>
  <div class="metric-box" style="border-left-color: #10b981;">
    <h4>Event Registrations</h4>
    <div class="val"><?php echo $totRegs; ?></div>
  </div>
  <div class="metric-box" style="border-left-color: #8b5cf6;">
    <h4>User Accounts</h4>
    <div class="val"><?php echo $totUsers; ?></div>
  </div>
</div>

<!-- Recent Event Registrations -->
<div class="card">
  <h2>Recent Event Registrations</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Student Name</th>
        <th>Enrollment</th>
        <th>Event Name</th>
        <th>Registration Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($latestRegs && mysqli_num_rows($latestRegs) > 0): ?>
        <?php while ($lr = mysqli_fetch_assoc($latestRegs)): ?>
          <tr>
            <td>#<?php echo $lr["id"]; ?></td>
            <td><strong><?php echo htmlspecialchars($lr["full_name"]); ?></strong></td>
            <td><?php echo htmlspecialchars($lr["enrollment_no"]); ?></td>
            <td><?php echo htmlspecialchars($lr["title"]); ?></td>
            <td><?php echo date("d-M-Y H:i", strtotime($lr["registration_date"])); ?></td>
            <td><span style="color: #16a34a; font-weight: bold;"><?php echo strtoupper($lr["status"]); ?></span></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" style="text-align: center; color: #64748b;">No recent registrations found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<!-- Security Audit Log Trail -->
<div class="card">
  <h2>Recent System Audit Trail</h2>
  <table>
    <thead>
      <tr>
        <th style="width: 50px;">Log ID</th>
        <th>Action</th>
        <th>Details</th>
        <th>IP Address</th>
        <th>Timestamp</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($latestLogs && mysqli_num_rows($latestLogs) > 0): ?>
        <?php while ($log = mysqli_fetch_assoc($latestLogs)): ?>
          <tr>
            <td>#<?php echo $log["id"]; ?></td>
            <td><code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px;"><?php echo htmlspecialchars($log["action"]); ?></code></td>
            <td><?php echo htmlspecialchars($log["details"]); ?></td>
            <td><?php echo htmlspecialchars($log["ip_address"]); ?></td>
            <td><?php echo date("d-M-Y H:i:s", strtotime($log["created_at"])); ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5" style="text-align: center; color: #64748b;">No audit records available.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
