<?php
// Practical 14: Login / Role Switcher for RBAC Testing
session_start();
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/logger.php";

// Handle Logout
if (isset($_GET["action"]) && $_GET["action"] === "logout") {
    if (isset($_SESSION["user_id"])) {
        record_audit_log($conn, $_SESSION["user_id"], "USER_LOGOUT", "User logged out");
    }
    session_destroy();
    header("Location: login.php");
    exit;
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selectedRole = $_POST["role"] ?? "admin";

    if ($selectedRole === "admin") {
        $_SESSION["logged_in"] = true;
        $_SESSION["user_id"] = 1;
        $_SESSION["username"] = "admin";
        $_SESSION["user_role"] = "admin";

        record_audit_log($conn, 1, "ADMIN_LOGIN", "Administrator accessed the dashboard");
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $_SESSION["logged_in"] = true;
        $_SESSION["user_id"] = 2;
        $_SESSION["username"] = "rahul24";
        $_SESSION["user_role"] = "student";

        record_audit_log($conn, 2, "STUDENT_LOGIN", "Student logged into the portal");
        header("Location: admin_dashboard.php"); // Will demonstrate RBAC block!
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StudentHub - RBAC Login (Practical 14)</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body style="background: #e2e8f0; display: flex; align-items: center; justify-content: center; min-height: 100vh;">

  <div style="background: white; padding: 30px; border-radius: 8px; max-width: 420px; width: 100%; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    <h1 style="font-size: 20px; color: #0f172a; text-align: center; margin-bottom: 5px;">Role-Based Access Demo</h1>
    <p style="font-size: 13px; color: #64748b; text-align: center; margin-bottom: 20px;">Practical 14: Dynamic Menu &amp; Audit Trail</p>

    <form action="login.php" method="POST">
      <div style="margin-bottom: 15px;">
        <label style="display: block; font-weight: bold; font-size: 13px; margin-bottom: 6px;">Select Role to Simulate:</label>
        <select name="role" style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 14px;">
          <option value="admin">Administrator (Role: admin &rarr; Full Access)</option>
          <option value="student">Student (Role: student &rarr; Restricted Access)</option>
        </select>
      </div>

      <button type="submit" class="btn-action" style="width: 100%; padding: 10px;">Proceed into Dashboard</button>
    </form>
  </div>

</body>
</html>
