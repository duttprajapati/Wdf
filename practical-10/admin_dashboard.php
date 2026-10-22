<?php
// Practical 10: Protected Administrator Dashboard
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/auth_check.php";

// Restrict access strictly to administrators
require_role("admin");

$username = $_SESSION["username"];

// Fetch summary metrics
$studentsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students"))["c"] ?? 0;
$eventsCount   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM events"))["c"] ?? 0;
$regsCount     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM registrations"))["c"] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StudentHub - Admin Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <nav class="top-nav" style="background-color: #0f172a;">
    <div class="brand">StudentHub &bull; Administration Control Center</div>
    <div class="user-info">
      <span>Logged in as: <strong><?php echo htmlspecialchars($username); ?></strong> (Role: ADMIN)</span>
      <a href="logout.php" class="btn-logout">Logout</a>
    </div>
  </nav>

  <div class="dashboard-container">
    <div class="card">
      <h2>Administrator Overview</h2>
      <p>System metrics and administrative controls. Verified with secure role-based session authentication.</p>

      <div style="display: flex; gap: 15px; margin-top: 20px;">
        <div style="flex: 1; background: #e0f2fe; padding: 15px; border-radius: 6px; text-align: center;">
          <h3 style="font-size: 14px; color: #0369a1;">Total Students</h3>
          <p style="font-size: 28px; font-weight: bold; color: #0c4a6e;"><?php echo $studentsCount; ?></p>
        </div>
        <div style="flex: 1; background: #fef3c7; padding: 15px; border-radius: 6px; text-align: center;">
          <h3 style="font-size: 14px; color: #b45309;">Campus Events</h3>
          <p style="font-size: 28px; font-weight: bold; color: #78350f;"><?php echo $eventsCount; ?></p>
        </div>
        <div style="flex: 1; background: #dcfce7; padding: 15px; border-radius: 6px; text-align: center;">
          <h3 style="font-size: 14px; color: #15803d;">Registrations</h3>
          <p style="font-size: 28px; font-weight: bold; color: #14532d;"><?php echo $regsCount; ?></p>
        </div>
      </div>
    </div>

    <div class="card">
      <h2>Session Diagnostic &amp; Security State</h2>
      <p><strong>Session ID:</strong> <code><?php echo session_id(); ?></code></p>
      <p><strong>User Role:</strong> <code><?php echo htmlspecialchars($_SESSION["user_role"]); ?></code></p>
      <p><strong>Inactivity Timeout Threshold:</strong> 600 seconds (10 mins)</p>
    </div>
  </div>

</body>
</html>
