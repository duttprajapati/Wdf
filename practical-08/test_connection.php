<?php
// Practical 8: Database Connection Test Page

require_once __DIR__ . "/db.php";

// Fetch list of tables in studenthub_db
$tablesResult = mysqli_query($conn, "SHOW TABLES");
$tables = [];
while ($row = mysqli_fetch_row($tablesResult)) {
    $tables[] = $row[0];
}

// Fetch record counts
$userCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users"))["cnt"] ?? 0;
$studentCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM students"))["cnt"] ?? 0;
$eventCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM events"))["cnt"] ?? 0;
$regCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM registrations"))["cnt"] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StudentHub - Database Connection Test (Practical 8)</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f0f4f8; margin: 30px; color: #333; }
    .card { background: white; padding: 25px; border-radius: 8px; max-width: 650px; margin: 0 auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    h1 { color: #003366; font-size: 22px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
    .status-ok { background: #d1fae5; color: #065f46; padding: 12px; border-radius: 4px; font-weight: bold; margin: 15px 0; }
    ul { margin: 10px 0 20px 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #cbd5e0; padding: 8px 12px; text-align: left; }
    th { background: #f8fafc; }
  </style>
</head>
<body>

  <div class="card">
    <h1>StudentHub Database Connection Test</h1>

    <div class="status-ok">
      ✅ MySQL Connection Successful! Connected to "<?php echo htmlspecialchars($db_name); ?>".
    </div>

    <p><strong>MySQL Server Info:</strong> <?php echo mysqli_get_server_info($conn); ?></p>
    <p><strong>Host Protocol:</strong> <?php echo mysqli_get_host_info($conn); ?></p>

    <h2 style="font-size: 16px; margin-top: 20px; color: #1e293b;">Verified Database Tables:</h2>
    <ul>
      <?php foreach ($tables as $t): ?>
        <li><code><?php echo htmlspecialchars($t); ?></code></li>
      <?php endforeach; ?>
    </ul>

    <h2 style="font-size: 16px; color: #1e293b;">Seed Records Found:</h2>
    <table>
      <thead>
        <tr>
          <th>Table Name</th>
          <th>Record Count</th>
        </tr>
      </thead>
      <tbody>
        <tr><td>users</td><td><?php echo $userCount; ?> records</td></tr>
        <tr><td>students</td><td><?php echo $studentCount; ?> records</td></tr>
        <tr><td>events</td><td><?php echo $eventCount; ?> records</td></tr>
        <tr><td>registrations</td><td><?php echo $regCount; ?> records</td></tr>
      </tbody>
    </table>
  </div>

</body>
</html>
