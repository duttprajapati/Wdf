<?php
// Practical 11: Edit Student Record
require_once __DIR__ . "/db.php";

$id = intval($_GET["id"] ?? 0);
if ($id <= 0) {
    header("Location: index.php");
    exit;
}

// Fetch existing student details
$res = mysqli_query($conn, "SELECT * FROM students WHERE id = $id LIMIT 1");
if (!$res || mysqli_num_rows($res) === 0) {
    header("Location: index.php?msg=error");
    exit;
}
$student = mysqli_fetch_assoc($res);
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
        $updateSql = "UPDATE students SET 
                        enrollment_no = '$enrollment',
                        full_name = '$fullname',
                        email = '$email',
                        mobile = '$mobile',
                        course = '$course',
                        semester = $semester
                      WHERE id = $id";

        if (mysqli_query($conn, $updateSql)) {
            header("Location: index.php?msg=updated");
            exit;
        } else {
            $error = "Update error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Student Record</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container" style="max-width: 600px;">
    <header>
      <h1>Edit Student Record #<?php echo $id; ?></h1>
      <a href="index.php" class="btn btn-primary">&larr; Back to Student List</a>
    </header>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="edit_student.php?id=<?php echo $id; ?>" method="POST" class="form-box">
      <div class="form-group">
        <label for="enrollment">Enrollment Number:</label>
        <input type="text" id="enrollment" name="enrollment" value="<?php echo htmlspecialchars($student["enrollment_no"]); ?>" required>
      </div>

      <div class="form-group">
        <label for="fullname">Student Full Name:</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($student["full_name"]); ?>" required>
      </div>

      <div class="form-group">
        <label for="email">College Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student["email"]); ?>" required>
      </div>

      <div class="form-group">
        <label for="mobile">Mobile Number:</label>
        <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($student["mobile"]); ?>" required>
      </div>

      <div class="form-group">
        <label for="course">Program / Department:</label>
        <select id="course" name="course" required>
          <option value="B.Tech IT" <?php if ($student["course"] === "B.Tech IT") echo "selected"; ?>>B.Tech IT</option>
          <option value="B.Tech CSE" <?php if ($student["course"] === "B.Tech CSE") echo "selected"; ?>>B.Tech CSE</option>
          <option value="B.Tech CE" <?php if ($student["course"] === "B.Tech CE") echo "selected"; ?>>B.Tech CE</option>
          <option value="BCA" <?php if ($student["course"] === "BCA") echo "selected"; ?>>BCA</option>
          <option value="MCA" <?php if ($student["course"] === "MCA") echo "selected"; ?>>MCA</option>
        </select>
      </div>

      <div class="form-group">
        <label for="semester">Semester:</label>
        <select id="semester" name="semester" required>
          <?php for ($s = 1; $s <= 8; $s++): ?>
            <option value="<?php echo $s; ?>" <?php if (intval($student["semester"]) === $s) echo "selected"; ?>><?php echo $s; ?>th Semester</option>
          <?php endfor; ?>
        </select>
      </div>

      <button type="submit" class="btn btn-warning" style="width: 100%; padding: 10px; font-size: 15px;">Update Student Record</button>
    </form>
  </div>

</body>
</html>
