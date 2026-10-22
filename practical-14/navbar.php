<?php
// Practical 14: Dynamic Role-Based Menu Component

$currentRole = $_SESSION["user_role"] ?? "guest";
$currentUsername = $_SESSION["username"] ?? "Guest";
?>
<nav class="role-nav">
  <div class="nav-brand">
    <strong>StudentHub</strong>
    <span class="role-pill <?php echo htmlspecialchars($currentRole); ?>">
      <?php echo strtoupper(htmlspecialchars($currentRole)); ?>
    </span>
  </div>

  <ul class="nav-items">
    <?php if ($currentRole === "admin"): ?>
      <!-- Admin Specific Menu Options -->
      <li><a href="admin_dashboard.php" class="active">📊 Dashboard</a></li>
      <li><a href="../practical-11/index.php">👥 Manage Students</a></li>
      <li><a href="../practical-12/index.php">📅 Manage Events</a></li>
      <li><a href="#audit-section">📜 Audit Logs</a></li>
    <?php elseif ($currentRole === "student"): ?>
      <!-- Student Specific Menu Options -->
      <li><a href="../practical-10/student_dashboard.php">🎓 Student Home</a></li>
      <li><a href="../practical-06/index.html">📅 Browse Events</a></li>
      <li><a href="../practical-02/profile.html">👤 My Profile</a></li>
      <li><a href="../practical-02/feedback.html">💬 Feedback</a></li>
    <?php else: ?>
      <!-- Guest Menu Options -->
      <li><a href="../practical-02/index.html">Home</a></li>
      <li><a href="../practical-02/events.html">Events</a></li>
      <li><a href="login.php">Sign In</a></li>
    <?php endif; ?>
  </ul>

  <div class="user-action">
    <?php if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true): ?>
      <span style="font-size: 13px; color: #cbd5e1;">👤 <?php echo htmlspecialchars($currentUsername); ?></span>
      <a href="login.php?action=logout" class="btn-logout-small">Logout</a>
    <?php else: ?>
      <a href="login.php" class="btn-login-small">Login</a>
    <?php endif; ?>
  </div>
</nav>
