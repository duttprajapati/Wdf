<?php
$pageTitle = "Home";
$activeNav = "home";
require_once __DIR__ . "/includes/header.php";

// Fetch featured 3 upcoming events from database
$eventsQuery = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC LIMIT 3");
?>

<section class="hero">
  <h1>Welcome to CHARUSAT StudentHub</h1>
  <p>The centralized digital gateway for students of Faculty of Technology &amp; Engineering. Discover campus hackathons, technical workshops, cultural nights, and manage your academic profile.</p>
  <div style="display: flex; justify-content: center; gap: 12px;">
    <a href="events.php" class="btn btn-warning" style="padding: 10px 20px; font-size: 14px;">Browse Campus Events</a>
    <?php if (!$isLoggedIn): ?>
      <a href="register.php" class="btn btn-primary" style="padding: 10px 20px; font-size: 14px;">Join StudentHub</a>
    <?php endif; ?>
  </div>
</section>

<h2 style="color: #003366; margin-bottom: 16px;">Featured Campus Highlights</h2>
<div class="grid-3col">
  <?php if ($eventsQuery && mysqli_num_rows($eventsQuery) > 0): ?>
    <?php while ($evt = mysqli_fetch_assoc($eventsQuery)): ?>
      <article class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
          <span style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">
            <?php echo htmlspecialchars($evt["category"]); ?>
          </span>
          <h3 style="margin: 8px 0; color: #003366; font-size: 17px;"><?php echo htmlspecialchars($evt["title"]); ?></h3>
          <p style="font-size: 13px; color: #64748b; margin-bottom: 8px;">
            📅 <?php echo date("d-M-Y", strtotime($evt["event_date"])); ?> | 📍 <?php echo htmlspecialchars($evt["venue"]); ?>
          </p>
          <p style="font-size: 13px; color: #334155; margin-bottom: 15px;">
            <?php echo htmlspecialchars(substr($evt["description"], 0, 100)) . "..."; ?>
          </p>
        </div>
        <a href="events.php" class="btn btn-primary" style="text-align: center;">View &amp; Register</a>
      </article>
    <?php endwhile; ?>
  <?php else: ?>
    <p>No upcoming events posted yet.</p>
  <?php endif; ?>
</div>

<div class="grid-2col" style="margin-top: 25px;">
  <div class="card">
    <h2>Why StudentHub?</h2>
    <p>Developed as an all-in-one portal adhering to HTML5 semantics, mobile-first responsive layout, and secure PHP/MySQL backend architecture.</p>
    <ul style="padding-left: 20px; margin-top: 10px; font-size: 14px;">
      <li>Automated online event registration</li>
      <li>Instant credential security with Bcrypt hashing</li>
      <li>Role-based access control (Admin &amp; Student)</li>
      <li>Zero reload live interaction via AJAX and Fetch API</li>
    </ul>
  </div>

  <div class="card" style="background: #eff6ff; border-left: 4px solid #2563eb;">
    <h2 style="color: #1e40af; border-color: #bfdbfe;">📢 Campus Announcements</h2>
    <ul style="padding-left: 20px; font-size: 14px; color: #1e3a8a;">
      <li style="margin-bottom: 8px;"><strong>Hack-CHARUSAT 2026:</strong> Registration deadline is November 14th. Form your teams now!</li>
      <li style="margin-bottom: 8px;"><strong>Attendance Reminder:</strong> Ensure minimum 80% attendance to qualify for lab exams.</li>
      <li><strong>Placement Cell:</strong> Pre-placement mock interviews begin next Monday in Seminar Hall 2.</li>
    </ul>
  </div>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
