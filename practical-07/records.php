<?php
// Practical 7: View Stored CSV and JSON Records

$csvFile = __DIR__ . "/data/students.csv";
$jsonFile = __DIR__ . "/data/students.json";

// Read CSV Records
$csvRows = [];
if (file_exists($csvFile)) {
    if (($handle = fopen($csvFile, "r")) !== false) {
        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            $csvRows[] = $row;
        }
        fclose($handle);
    }
}

// Read JSON Records
$jsonRecords = [];
if (file_exists($jsonFile)) {
    $rawJson = file_get_contents($jsonFile);
    $decoded = json_decode($rawJson, true);
    if (is_array($decoded)) {
        $jsonRecords = $decoded;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - View Stored Records (Practical 7)</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container" style="max-width: 950px;">
    <header>
      <h1>Stored StudentHub Records</h1>
      <p class="subtitle">Practical 7: Records read from CSV and JSON file storage</p>
    </header>

    <div style="margin-bottom: 20px;">
      <a href="index.php" class="btn-submit" style="text-decoration: none; display: inline-block;">&larr; Back to Registration Form</a>
    </div>

    <!-- Section 1: CSV Records -->
    <h2 style="color: #003366; font-size: 18px; margin-top: 25px;">1. Records from CSV File (data/students.csv)</h2>
    <?php if (!empty($csvRows)): ?>
      <table>
        <thead>
          <tr>
            <?php foreach ($csvRows[0] as $colHeader): ?>
              <th><?php echo htmlspecialchars($colHeader); ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php for ($i = 1; $i < count($csvRows); $i++): ?>
            <tr>
              <?php foreach ($csvRows[$i] as $cell): ?>
                <td><?php echo htmlspecialchars($cell); ?></td>
              <?php endforeach; ?>
            </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="color: #666; font-style: italic; margin-top: 10px;">No records found in CSV file.</p>
    <?php endif; ?>

    <!-- Section 2: JSON Records -->
    <h2 style="color: #003366; font-size: 18px; margin-top: 35px;">2. Records from JSON File (data/students.json)</h2>
    <?php if (!empty($jsonRecords)): ?>
      <table>
        <thead>
          <tr>
            <th>Timestamp</th>
            <th>Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Course</th>
            <th>Semester</th>
            <th>Message</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($jsonRecords as $record): ?>
            <tr>
              <td><?php echo htmlspecialchars($record["timestamp"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($record["name"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($record["email"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($record["mobile"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($record["course"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($record["semester"] ?? ""); ?></td>
              <td><?php echo htmlspecialchars($record["message"] ?? ""); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="color: #666; font-style: italic; margin-top: 10px;">No records found in JSON file.</p>
    <?php endif; ?>

  </div>

</body>
</html>
