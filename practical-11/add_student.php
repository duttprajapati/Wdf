<?php
// Practical 11: Add Student Record
require_once __DIR__ . "/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enrollment = trim(mysqli_real_escape_string($conn, $_POST["enrollment"] ?? ""));
    $fullname   = trim(mysqli_real_escape_string($conn, $_POST["fullname"] ?? ""));
    $email      = trim(mysqli_real_escape_string($conn, $_POST["email"] ?? ""));
    $mobile     = trim(mysqli_real_escape_string($conn, $_POST["mobile"] ?? ""));
    $course     = trim(mysqli_real_escape_string($conn, $_POST["course"] ?? ""));
    $semester   = intval($_POST["semester"] ?? 1);

    if (empty($enrollment) || empty($fullname) || empty($email) || empty($mobile) || empty($course)) {
        $error = "All fields are required.";
    } else {
        // Check duplicate enrollment
        $check = mysqli_query($conn, "SELECT id FROM students WHERE enrollment_no = '$enrollment' LIMIT 1");
        if (mysqli_num_rows($check) > 0) {
            $error = "Enrollment number '$enrollment' already exists in the system.";
        } else {
            $insertSql = "INSERT INTO students (enrollment_no, full_name, email, mobile, course, semester) 
                          VALUES ('$enrollment', '$fullname', '$email', '$mobile', '$course', $semester)";
            if (mysqli_query($conn, $insertSql)) {
                header("Location: index.php?msg=added");
                exit;
            } else {
                $error = "Insert error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Student Record</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container" style="max-width: 600px;">
    <header>
      <h1>Add New Student Record</h1>
      <a href="index.php" class="btn btn-primary">&larr; Back to Student List</a>
    </header>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="add_student.php" method="POST" class="form-box">
      <div class="form-group">
        <label for="enrollment">Enrollment Number: *</label>
        <input type="text" id="enrollment" name="enrollment" placeholder="e.g. 24IT021" required>
      </div>

      <div class="form-group">
        <label for="fullname">Student Full Name: *</label>
        <input type="text" id="fullname" name="fullname" placeholder="e.g. Priyanshu Patel" required>
      </div>

      <div class="form-group">
        <label for="email">College Email: *</label>
        <input type="email" id="email" name="email" placeholder="e.g. 24it021@charusat.edu.in" required>
      </div>

      <div class="form-group">
        <label for="mobile">Mobile Number: *</label>
        <input type="tel" id="mobile" name="mobile" placeholder="10-digit number" required>
      </div>

      <div class="form-group">
        <label for="course">Program / Department: *</label>
        <select id="course" name="course" required>
          <option value="B.Tech IT">B.Tech IT</option>
          <option value="B.Tech CSE">B.Tech CSE</option>
          <option value="B.Tech CE">B.Tech CE</option>
          <option value="BCA">BCA</option>
          <option value="MCA">MCA</option>
        </select>
      </div>

      <div class="form-group">
        <label for="semester">Semester: *</label>
        <select id="semester" name="semester" required>
          <option value="1">1st Semester</option>
          <option value="2">2nd Semester</option>
          <option value="3" selected>3rd Semester</option>
          <option value="4">4th Semester</option>
          <option value="5">5th Semester</option>
          <option value="6">6th Semester</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success" style="width: 100%; padding: 10px; font-size: 15px;">Save Student Record</button>
    </form>
  </div>

</body>
</html>
