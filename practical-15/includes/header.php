<?php
// Practical 15: Unified Semantic Header Include
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/db.php";

$isLoggedIn = isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
$userRole   = $_SESSION["user_role"] ?? "guest";
$userName   = $_SESSION["username"] ?? "Guest";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $pageTitle ?? "StudentHub Portal"; ?> - CHARUSAT FTE</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <header class="site-header">
    <div class="header-top">
      <a href="index.php" class="brand">Student<span>Hub</span> Portal</a>
      <div class="user-status">
        <span class="role-badge <?php echo htmlspecialchars($userRole); ?>">
          Role: <?php echo htmlspecialchars($userRole); ?>
        </span>
        <?php if ($isLoggedIn): ?>
          <span>Hi, <strong><?php echo htmlspecialchars($userName); ?></strong></span>
          <a href="logout.php" class="btn btn-danger" style="padding: 4px 10px; font-size: 11px;">Logout</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-primary" style="padding: 4px 12px; font-size: 12px;">Login</a>
          <a href="register.php" class="btn btn-warning" style="padding: 4px 12px; font-size: 12px;">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- Dynamic Semantic Navigation -->
  <nav class="main-nav" aria-label="Main Navigation">
    <ul>
      <li><a href="index.php" class="<?php echo ($activeNav ?? '') === 'home' ? 'active' : ''; ?>">Home</a></li>
      <li><a href="about.php" class="<?php echo ($activeNav ?? '') === 'about' ? 'active' : ''; ?>">About</a></li>
      <li><a href="events.php" class="<?php echo ($activeNav ?? '') === 'events' ? 'active' : ''; ?>">Campus Events</a></li>
      <li><a href="ajax_events.php" class="<?php echo ($activeNav ?? '') === 'ajax' ? 'active' : ''; ?>">Live AJAX Explorer</a></li>

      <?php if ($userRole === "student"): ?>
        <li><a href="student_dashboard.php" class="<?php echo ($activeNav ?? '') === 'dashboard' ? 'active' : ''; ?>">My Dashboard</a></li>
      <?php elseif ($userRole === "admin"): ?>
        <li><a href="admin_dashboard.php" class="<?php echo ($activeNav ?? '') === 'admin' ? 'active' : ''; ?>">Admin Dashboard</a></li>
        <li><a href="manage_students.php" class="<?php echo ($activeNav ?? '') === 'students' ? 'active' : ''; ?>">Manage Students</a></li>
        <li><a href="manage_events.php" class="<?php echo ($activeNav ?? '') === 'manage_events' ? 'active' : ''; ?>">Manage Events</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <main class="main-content">
