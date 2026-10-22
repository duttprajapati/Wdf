<?php
// Practical 10: Protected Student Dashboard
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/auth_check.php";

// Restrict access strictly to students
require_role("student");

$userId = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Fetch student profile from database
$sql = "SELECT * FROM students WHERE user_id = '$userId' LIMIT 1";
$res = mysqli_query($conn, $sql);
$student = ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StudentHub - Student Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <nav class="top-nav">
    <div class="brand">StudentHub &bull; Student Portal</div>
    <div class="user-info">
      <span>Logged in as: <strong><?php echo htmlspecialchars($username); ?></strong> (Role: student)</span>
      <a href="logout.php" class="btn-logout">Logout</a>
    </div>
  </nav>

  <div class="dashboard-container">
    <div class="card">
      <h2>Student Profile Dashboard</h2>
      <p>Welcome, <strong><?php echo htmlspecialchars($student["full_name"] ?? $username); ?></strong>! You have successfully accessed your protected session.</p>

      <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 14px;">
        <tr style="border-bottom: 1px solid #e2e8f0;"><td style="padding: 8px; font-weight: bold;">Enrollment No:</td><td><?php echo htmlspecialchars($student["enrollment_no"] ?? "N/A"); ?></td></tr>
        <tr style="border-bottom: 1px solid #e2e8f0;"><td style="padding: 8px; font-weight: bold;">Email:</td><td><?php echo htmlspecialchars($student["email"] ?? "N/A"); ?></td></tr>
        <tr style="border-bottom: 1px solid #e2e8f0;"><td style="padding: 8px; font-weight: bold;">Course / Department:</td><td><?php echo htmlspecialchars($student["course"] ?? "N/A"); ?></td></tr>
        <tr style="border-bottom: 1px solid #e2e8f0;"><td style="padding: 8px; font-weight: bold;">Semester:</td><td><?php echo htmlspecialchars($student["semester"] ?? "N/A"); ?></td></tr>
        <tr><td style="padding: 8px; font-weight: bold;">Session ID (Regenerated):</td><td><code><?php echo session_id(); ?></code></td></tr>
      </table>
    </div>

    <div class="card" style="background: #eff6ff; border-left: 5px solid #3b82f6;">
      <h3 style="color: #1e40af; margin-bottom: 8px;">Active Session Protection</h3>
      <p style="font-size: 13px; color: #1e3a8a;">
        Your session is guarded with inactivity timeout (10 minutes). If you remain idle, you will be automatically logged out for security.
      </p>
    </div>
  </div>

</body>
</html>
