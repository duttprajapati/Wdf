<?php
// Practical 11: Student Management CRUD - View, Search & Filter
require_once __DIR__ . "/db.php";

$searchKeyword = trim($_GET["search"] ?? "");
$courseFilter  = trim($_GET["course"] ?? "");

// Build Dynamic SQL Query
$sql = "SELECT * FROM students WHERE 1=1";

if (!empty($searchKeyword)) {
    $escapedSearch = mysqli_real_escape_string($conn, $searchKeyword);
    $sql .= " AND (full_name LIKE '%$escapedSearch%' OR email LIKE '%$escapedSearch%' OR enrollment_no LIKE '%$escapedSearch%')";
}

if (!empty($courseFilter) && $courseFilter !== "ALL") {
    $escapedCourse = mysqli_real_escape_string($conn, $courseFilter);
    $sql .= " AND course = '$escapedCourse'";
}

$sql .= " ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$studentCount = $result ? mysqli_num_rows($result) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - Student Management CRUD (Practical 11)</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="container">
    <header>
      <div>
        <h1>Student Management Module</h1>
        <p style="color: #64748b; font-size: 13px;">Practical 11: MySQLi CRUD with Search &amp; Filter</p>
      </div>
      <div>
        <a href="add_student.php" class="btn btn-success">➕ Add New Student</a>
      </div>
    </header>

    <!-- Feedback Flash Messages -->
    <?php if (isset($_GET["msg"])): ?>
      <?php if ($_GET["msg"] === "added"): ?>
        <div class="alert alert-success">✅ Student record added successfully!</div>
      <?php elseif ($_GET["msg"] === "updated"): ?>
        <div class="alert alert-success">✅ Student record updated successfully!</div>
      <?php elseif ($_GET["msg"] === "deleted"): ?>
        <div class="alert alert-success">✅ Student record deleted successfully!</div>
      <?php elseif ($_GET["msg"] === "error"): ?>
        <div class="alert alert-danger">❌ Operation failed. Please check inputs.</div>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Search & Filter Form (GET method) -->
    <form action="index.php" method="GET" class="filter-bar">
      <input type="text" name="search" value="<?php echo htmlspecialchars($searchKeyword); ?>" placeholder="🔍 Search name, email, enrollment..." style="flex: 2;">

      <select name="course" style="flex: 1;">
        <option value="ALL">All Departments / Courses</option>
        <option value="B.Tech IT" <?php if ($courseFilter === "B.Tech IT") echo "selected"; ?>>B.Tech IT</option>
        <option value="B.Tech CSE" <?php if ($courseFilter === "B.Tech CSE") echo "selected"; ?>>B.Tech CSE</option>
        <option value="B.Tech CE" <?php if ($courseFilter === "B.Tech CE") echo "selected"; ?>>B.Tech CE</option>
        <option value="BCA" <?php if ($courseFilter === "BCA") echo "selected"; ?>>BCA</option>
        <option value="MCA" <?php if ($courseFilter === "MCA") echo "selected"; ?>>MCA</option>
      </select>

      <button type="submit" class="btn btn-primary">Filter</button>
      <a href="index.php" class="btn" style="background:#94a3b8; color:white;">Reset</a>
    </form>

    <p style="font-size: 13px; color: #64748b; margin-bottom: 10px;">
      Showing <strong><?php echo $studentCount; ?></strong> student records
    </p>

    <!-- Student Table -->
    <?php if ($studentCount > 0): ?>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Enrollment</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Mobile</th>
            <th>Course</th>
            <th>Semester</th>
            <th style="text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo $row["id"]; ?></td>
              <td><strong><?php echo htmlspecialchars($row["enrollment_no"]); ?></strong></td>
              <td><?php echo htmlspecialchars($row["full_name"]); ?></td>
              <td><?php echo htmlspecialchars($row["email"]); ?></td>
              <td><?php echo htmlspecialchars($row["mobile"]); ?></td>
              <td><?php echo htmlspecialchars($row["course"]); ?></td>
              <td><?php echo htmlspecialchars($row["semester"]); ?></td>
              <td style="text-align: center; white-space: nowrap;">
                <a href="edit_student.php?id=<?php echo $row["id"]; ?>" class="btn btn-warning">Edit</a>
                <a href="delete_student.php?id=<?php echo $row["id"]; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to permanently delete this student record?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div style="text-align: center; padding: 40px; color: #64748b; background: #f8fafc; border-radius: 4px;">
        <p>No student records match your search or filter criteria.</p>
        <a href="index.php" style="color: #0056b3; font-weight: bold; margin-top: 10px; display: inline-block;">Clear search filters</a>
      </div>
    <?php endif; ?>

  </div>

</body>
</html>
