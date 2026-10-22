<?php
$pageTitle = "Manage Students";
$activeNav = "students";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/logger.php";

require_admin();

$msg = "";
$msgType = "success";

// Handle Delete Student
if (isset($_GET["action"]) && $_GET["action"] === "delete") {
    $delId = intval($_GET["id"] ?? 0);
    if ($delId > 0) {
        $delSql = "DELETE FROM students WHERE id = $delId";
        if (mysqli_query($conn, $delSql)) {
            log_system_action($conn, $_SESSION["user_id"], "STUDENT_DELETE", "Deleted student record ID #$delId");
            $msg = "Student record deleted successfully.";
        }
    }
}

// Handle Add / Update Student
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studId     = intval($_POST["student_id"] ?? 0);
    $enrollment = trim(mysqli_real_escape_string($conn, $_POST["enrollment"] ?? ""));
    $fullname   = trim(mysqli_real_escape_string($conn, $_POST["fullname"] ?? ""));
    $email      = trim(mysqli_real_escape_string($conn, $_POST["email"] ?? ""));
    $mobile     = trim(mysqli_real_escape_string($conn, $_POST["mobile"] ?? ""));
    $course     = trim(mysqli_real_escape_string($conn, $_POST["course"] ?? ""));
    $semester   = intval($_POST["semester"] ?? 1);

    if ($studId > 0) {
        // UPDATE
        $updateSql = "UPDATE students SET 
                        enrollment_no = '$enrollment',
                        full_name = '$fullname',
                        email = '$email',
                        mobile = '$mobile',
                        course = '$course',
                        semester = $semester
                      WHERE id = $studId";
        if (mysqli_query($conn, $updateSql)) {
            log_system_action($conn, $_SESSION["user_id"], "STUDENT_UPDATE", "Updated student #$studId ($fullname)");
            $msg = "Student updated successfully.";
        }
    } else {
        // INSERT
        $insertSql = "INSERT INTO students (enrollment_no, full_name, email, mobile, course, semester) 
                      VALUES ('$enrollment', '$fullname', '$email', '$mobile', '$course', $semester)";
        if (mysqli_query($conn, $insertSql)) {
            $newId = mysqli_insert_id($conn);
            log_system_action($conn, $_SESSION["user_id"], "STUDENT_ADD", "Added student #$newId ($fullname)");
            $msg = "Student added successfully.";
        }
    }
}

// Search & Filter
$search = trim($_GET["search"] ?? "");
$courseFilter = trim($_GET["course"] ?? "");

$sql = "SELECT * FROM students WHERE 1=1";
if (!empty($search)) {
    $esc = mysqli_real_escape_string($conn, $search);
    $sql .= " AND (full_name LIKE '%$esc%' OR email LIKE '%$esc%' OR enrollment_no LIKE '%$esc%')";
}
if (!empty($courseFilter) && $courseFilter !== "ALL") {
    $escC = mysqli_real_escape_string($conn, $courseFilter);
    $sql .= " AND course = '$escC'";
}
$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="card">
  <h2>Student Management (Administrator Module)</h2>
  
  <?php if (!empty($msg)): ?>
    <div class="alert alert-<?php echo $msgType; ?>"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <!-- Search & Filter Bar -->
  <form action="manage_students.php" method="GET" style="display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="🔍 Search student name, enrollment, email..." style="flex: 2; min-width: 220px;">
    
    <select name="course" style="flex: 1; min-width: 150px;">
      <option value="ALL">All Departments</option>
      <option value="B.Tech IT" <?php if ($courseFilter === "B.Tech IT") echo "selected"; ?>>B.Tech IT</option>
      <option value="B.Tech CSE" <?php if ($courseFilter === "B.Tech CSE") echo "selected"; ?>>B.Tech CSE</option>
      <option value="B.Tech CE" <?php if ($courseFilter === "B.Tech CE") echo "selected"; ?>>B.Tech CE</option>
      <option value="BCA" <?php if ($courseFilter === "BCA") echo "selected"; ?>>BCA</option>
      <option value="MCA" <?php if ($courseFilter === "MCA") echo "selected"; ?>>MCA</option>
    </select>

    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="manage_students.php" class="btn" style="background: #94a3b8; color: white;">Reset</a>
  </form>

  <!-- Add New Student Form (Collapsible/Inline) -->
  <details style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
    <summary style="font-weight: bold; cursor: pointer; color: #003366;">➕ Add New Student Record</summary>
    <form action="manage_students.php" method="POST" style="margin-top: 15px;">
      <div class="grid-2col">
        <div class="form-group">
          <label>Enrollment No: *</label>
          <input type="text" name="enrollment" required placeholder="e.g. 24IT101">
        </div>
        <div class="form-group">
          <label>Full Name: *</label>
          <input type="text" name="fullname" required placeholder="e.g. Yash Patel">
        </div>
      </div>
      <div class="grid-2col">
        <div class="form-group">
          <label>College Email: *</label>
          <input type="email" name="email" required placeholder="student@charusat.edu.in">
        </div>
        <div class="form-group">
          <label>Mobile Number: *</label>
          <input type="tel" name="mobile" required placeholder="9876543210">
        </div>
      </div>
      <div class="grid-2col">
        <div class="form-group">
          <label>Course: *</label>
          <select name="course" required>
            <option value="B.Tech IT">B.Tech IT</option>
            <option value="B.Tech CSE">B.Tech CSE</option>
            <option value="B.Tech CE">B.Tech CE</option>
            <option value="BCA">BCA</option>
            <option value="MCA">MCA</option>
          </select>
        </div>
        <div class="form-group">
          <label>Semester: *</label>
          <select name="semester" required>
            <option value="1">1st Semester</option>
            <option value="2">2nd Semester</option>
            <option value="3" selected>3rd Semester</option>
            <option value="4">4th Semester</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-success">Save Student Record</button>
    </form>
  </details>

  <!-- Students Table -->
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Enrollment</th>
        <th>Full Name</th>
        <th>Email</th>
        <th>Course</th>
        <th>Semester</th>
        <th style="text-align: center;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?php echo $row["id"]; ?></td>
            <td><strong><?php echo htmlspecialchars($row["enrollment_no"]); ?></strong></td>
            <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
            <td><?php echo htmlspecialchars($row["email"]); ?></td>
            <td><?php echo htmlspecialchars($row["course"]); ?></td>
            <td><?php echo htmlspecialchars($row["semester"]); ?></td>
            <td style="text-align: center; white-space: nowrap;">
              <a href="manage_students.php?action=delete&id=<?php echo $row["id"]; ?>" class="btn btn-danger" style="padding: 3px 8px; font-size: 11px;" onclick="return confirm('Delete student <?php echo htmlspecialchars($row["full_name"]); ?>?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="7" style="text-align:center;">No students found matching query.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
