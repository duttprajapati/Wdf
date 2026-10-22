<?php
// Practical 7: PHP Form Processing with Server-Side Validation and CSV/JSON Storage

// Define variables to store form data & error messages
$name = $email = $mobile = $course = $semester = $message = "";
$errors = [];
$successMessage = "";

// Check if form was submitted using HTTP POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Sanitize & Retrieve Input Data
    $name     = isset($_POST["name"]) ? trim(htmlspecialchars($_POST["name"])) : "";
    $email    = isset($_POST["email"]) ? trim(htmlspecialchars($_POST["email"])) : "";
    $mobile   = isset($_POST["mobile"]) ? trim(htmlspecialchars($_POST["mobile"])) : "";
    $course   = isset($_POST["course"]) ? trim(htmlspecialchars($_POST["course"])) : "";
    $semester = isset($_POST["semester"]) ? trim(htmlspecialchars($_POST["semester"])) : "";
    $message  = isset($_POST["message"]) ? trim(htmlspecialchars($_POST["message"])) : "";
    $storageFormat = isset($_POST["storage_format"]) ? $_POST["storage_format"] : "both";

    // 2. Server-Side Validations
    // Check Full Name
    if (empty($name)) {
        $errors[] = "Full Name is required.";
    } elseif (!preg_match("/^[a-zA-Z\s]{3,50}$/", $name)) {
        $errors[] = "Full Name must contain only letters and spaces (min 3 characters).";
    }

    // Check Email Address
    if (empty($email)) {
        $errors[] = "Email address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please provide a valid email address.";
    }

    // Check Mobile Number
    if (empty($mobile)) {
        $errors[] = "Mobile number is required.";
    } elseif (!preg_match("/^[6-9]\d{9}$/", $mobile)) {
        $errors[] = "Mobile number must be 10 digits starting with 6, 7, 8, or 9.";
    }

    // Check Course
    if (empty($course)) {
        $errors[] = "Please select a course.";
    }

    // Check Semester
    if (empty($semester)) {
        $errors[] = "Please select a semester.";
    }

    // 3. If No Errors, Store Data into CSV and/or JSON
    if (empty($errors)) {
        $timestamp = date("Y-m-d H:i:s");
        $dataDir = __DIR__ . "/data";

        // Create data directory if it doesn't exist
        if (!is_dir($dataDir)) {
            mkdir($dataDir, 0777, true);
        }

        $storedLocations = [];

        // --- A. Save to CSV File ---
        if ($storageFormat === "csv" || $storageFormat === "both") {
            $csvFilePath = $dataDir . "/students.csv";
            $isNewFile = !file_exists($csvFilePath);

            $fileHandle = fopen($csvFilePath, "a");
            if ($fileHandle) {
                // If file is newly created, write header row first
                if ($isNewFile) {
                    fputcsv($fileHandle, ["Timestamp", "Name", "Email", "Mobile", "Course", "Semester", "Message"]);
                }
                // Write submitted student record
                fputcsv($fileHandle, [$timestamp, $name, $email, $mobile, $course, $semester, $message]);
                fclose($fileHandle);
                $storedLocations[] = "CSV file (students.csv)";
            } else {
                $errors[] = "Could not open CSV file for writing.";
            }
        }

        // --- B. Save to JSON File ---
        if ($storageFormat === "json" || $storageFormat === "both") {
            $jsonFilePath = $dataDir . "/students.json";
            $existingRecords = [];

            if (file_exists($jsonFilePath)) {
                $jsonContent = file_get_contents($jsonFilePath);
                $existingRecords = json_decode($jsonContent, true);
                if (!is_array($existingRecords)) {
                    $existingRecords = [];
                }
            }

            // Append new record
            $existingRecords[] = [
                "timestamp" => $timestamp,
                "name"      => $name,
                "email"     => $email,
                "mobile"    => $mobile,
                "course"    => $course,
                "semester"  => $semester,
                "message"   => $message
            ];

            // Save back formatted JSON
            if (file_put_contents($jsonFilePath, json_encode($existingRecords, JSON_PRETTY_PRINT))) {
                $storedLocations[] = "JSON file (students.json)";
            } else {
                $errors[] = "Could not save to JSON file.";
            }
        }

        // If file write was successful
        if (empty($errors)) {
            $successMessage = "Record saved successfully to " . implode(" and ", $storedLocations) . "!";
            // Reset input values
            $name = $email = $mobile = $course = $semester = $message = "";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - PHP Form Processing (Practical 7)</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container">
    <header>
      <h1>StudentHub Registration &amp; Inquiry Form</h1>
      <p class="subtitle">Practical 7: Server-Side Validation (PHP POST) &amp; CSV/JSON File Storage</p>
    </header>

    <!-- Success Message -->
    <?php if (!empty($successMessage)): ?>
      <div class="alert-success">
        ✅ <?php echo $successMessage; ?>
        <br><a href="records.php" style="color: #065f46; font-weight: bold;">Click here to view saved records &rarr;</a>
      </div>
    <?php endif; ?>

    <!-- Error Messages List -->
    <?php if (!empty($errors)): ?>
      <div class="alert-danger">
        <strong>Please correct the following errors:</strong>
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?php echo $err; ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <!-- Form submitted with POST method to self -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

      <div class="form-group">
        <label for="name">Student Full Name: *</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="e.g. Aarav Sharma" required>
      </div>

      <div class="form-group">
        <label for="email">College Email: *</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="e.g. 24it001@charusat.edu.in" required>
      </div>

      <div class="form-group">
        <label for="mobile">Mobile Number (10 Digits): *</label>
        <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" placeholder="e.g. 9876543210" required>
      </div>

      <div class="form-group">
        <label for="course">Academic Program: *</label>
        <select id="course" name="course" required>
          <option value="">-- Select Course --</option>
          <option value="B.Tech IT" <?php if ($course === "B.Tech IT") echo "selected"; ?>>B.Tech - Information Technology</option>
          <option value="B.Tech CSE" <?php if ($course === "B.Tech CSE") echo "selected"; ?>>B.Tech - Computer Science</option>
          <option value="BCA" <?php if ($course === "BCA") echo "selected"; ?>>BCA - Computer Applications</option>
          <option value="MCA" <?php if ($course === "MCA") echo "selected"; ?>>MCA - Master of Computer Applications</option>
        </select>
      </div>

      <div class="form-group">
        <label for="semester">Current Semester: *</label>
        <select id="semester" name="semester" required>
          <option value="">-- Select Semester --</option>
          <option value="1st" <?php if ($semester === "1st") echo "selected"; ?>>1st Semester</option>
          <option value="2nd" <?php if ($semester === "2nd") echo "selected"; ?>>2nd Semester</option>
          <option value="3rd" <?php if ($semester === "3rd") echo "selected"; ?>>3rd Semester</option>
          <option value="4th" <?php if ($semester === "4th") echo "selected"; ?>>4th Semester</option>
        </select>
      </div>

      <div class="form-group">
        <label for="storage_format">Choose Storage Format: *</label>
        <select id="storage_format" name="storage_format">
          <option value="both">Store in Both (CSV &amp; JSON)</option>
          <option value="csv">Store in CSV File Only</option>
          <option value="json">Store in JSON File Only</option>
        </select>
      </div>

      <div class="form-group">
        <label for="message">Remarks / Reason for Registration:</label>
        <textarea id="message" name="message" rows="3" placeholder="Add any comments or questions..."><?php echo htmlspecialchars($message); ?></textarea>
      </div>

      <button type="submit" class="btn-submit">Submit Form</button>
    </form>

    <div class="nav-links">
      <a href="records.php">📋 View All Saved Records (CSV &amp; JSON)</a>
    </div>
  </div>

</body>
</html>
