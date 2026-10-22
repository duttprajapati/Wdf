<?php
// Practical 14: Admin Dashboard with RBAC, Dynamic Menu, and Audit Log
session_start();
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/logger.php";

// RBAC Guard: Restrict strictly to admin
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["user_role"] !== "admin") {
    // Show Access Denied UI
    include __DIR__ . "/navbar.php";
    echo "<div style='max-width: 600px; margin: 40px auto; background: #fee2e2; border: 1px solid #fecaca; color: #991b1b; padding: 25px; border-radius: 8px; text-align: center;'>";
    echo "<h2 style='margin-bottom: 10px;'>⛔ 403 Forbidden Access</h2>";
    echo "<p>Your account role (<strong>" . htmlspecialchars($_SESSION["user_role"]) . "</strong>) does not have administrative clearance to access this control dashboard.</p>";
    echo "<p style='margin-top: 15px;'><a href='login.php?action=logout' style='color: #991b1b; font-weight: bold;'>Click here to switch account</a></p>";
    echo "</div>";
    exit;
}

// Handle Manual Audit Log Trigger
$actionNotice = "";
if (isset($_POST["trigger_maintenance"])) {
    record_audit_log($conn, $_SESSION["user_id"], "MAINTENANCE_TRIGGER", "Admin ran manual database maintenance check");
    $actionNotice = "Manual maintenance routine triggered and recorded in audit log.";
}

// 1. Fetch Dynamic Statistics from Database
$totalStudents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students"))["c"] ?? 0;
$totalEvents   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM events"))["c"] ?? 0;
$totalRegs     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM registrations"))["c"] ?? 0;
$totalUsers    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))["c"] ?? 0;

// 2. Fetch Latest 5 Event Registrations with Join
$latestRegsSql = "SELECT r.id, r.registration_date, r.status, s.full_name, s.enrollment_no, e.title as event_title 
                  FROM registrations r
                  JOIN students s ON r.student_id = s.id
                  JOIN events e ON r.event_id = e.id
                  ORDER BY r.id DESC LIMIT 5";
$latestRegs = mysqli_query($conn, $latestRegsSql);

// 3. Fetch Recent 10 Audit Logs
$auditLogsSql = "SELECT * FROM audit_logs ORDER BY id DESC LIMIT 10";
$auditLogs = mysqli_query($conn, $auditLogsSql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - Admin Dashboard (Practical 14)</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Dynamic Role-Based Menu -->
  <?php include __DIR__ . "/navbar.php"; ?>

  <div class="dashboard-container">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <div>
        <h1 style="font-size: 22px; color: #0f172a;">Executive Admin Dashboard</h1>
        <p style="color: #64748b; font-size: 13px;">Real-time campus metrics, registration oversight, and security audit trail</p>
      </div>

      <form action="admin_dashboard.php" method="POST">
        <button type="submit" name="trigger_maintenance" class="btn-action">⚡ Run Maintenance Log</button>
      </form>
    </div>

    <?php if (!empty($actionNotice)): ?>
      <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 14px;">
        ✅ <?php echo htmlspecialchars($actionNotice); ?>
      </div>
    <?php endif; ?>

    <!-- 1. Statistics Metric Cards -->
    <section class="stats-grid">
      <div class="stat-tile" style="border-left-color: #2563eb;">
        <h3>Enrolled Students</h3>
        <div class="number"><?php echo $totalStudents; ?></div>
      </div>
      <div class="stat-tile" style="border-left-color: #f59e0b;">
        <h3>Published Events</h3>
        <div class="number"><?php echo $totalEvents; ?></div>
      </div>
      <div class="stat-tile" style="border-left-color: #10b981;">
        <h3>Total Registrations</h3>
        <div class="number"><?php echo $totalRegs; ?></div>
      </div>
      <div class="stat-tile" style="border-left-color: #8b5cf6;">
        <h3>User Accounts</h3>
        <div class="number"><?php echo $totalUsers; ?></div>
      </div>
    </section>

    <!-- 2. Latest Event Registrations Table -->
    <section class="card">
      <h2>Recent Event Registrations (Cross-Table Join)</h2>
      <table>
        <thead>
          <tr>
            <th>Reg ID</th>
            <th>Student Name</th>
            <th>Enrollment</th>
            <th>Event Name</th>
            <th>Date Registered</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($latestRegs && mysqli_num_rows($latestRegs) > 0): ?>
            <?php while ($r = mysqli_fetch_assoc($latestRegs)): ?>
              <tr>
                <td>#<?php echo $r["id"]; ?></td>
                <td><strong><?php echo htmlspecialchars($r["full_name"]); ?></strong></td>
                <td><?php echo htmlspecialchars($r["enrollment_no"]); ?></td>
                <td><?php echo htmlspecialchars($r["event_title"]); ?></td>
                <td><?php echo date("d-M-Y H:i", strtotime($r["registration_date"])); ?></td>
                <td><span style="color: green; font-weight: bold;"><?php echo strtoupper($r["status"]); ?></span></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">No recent registrations recorded.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <!-- 3. Security Audit Trail Section -->
    <section class="card" id="audit-section">
      <h2>System Audit Trail (`audit_logs` Table)</h2>
      <p style="font-size: 13px; color: #64748b; margin-bottom: 12px;">Traceability log capturing admin and security-sensitive operations.</p>
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
          <?php if ($auditLogs && mysqli_num_rows($auditLogs) > 0): ?>
            <?php while ($log = mysqli_fetch_assoc($auditLogs)): ?>
              <tr>
                <td>#<?php echo $log["id"]; ?></td>
                <td><code style="background: #e2e8f0; padding: 2px 6px; border-radius: 4px;"><?php echo htmlspecialchars($log["action"]); ?></code></td>
                <td><?php echo htmlspecialchars($log["details"]); ?></td>
                <td><?php echo htmlspecialchars($log["ip_address"]); ?></td>
                <td><?php echo date("d-M-Y H:i:s", strtotime($log["created_at"])); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No audit logs found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

  </div>

</body>
</html>
