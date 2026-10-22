<?php
$pageTitle = "Register";
$activeNav = "register";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/logger.php";

$username = $fullname = $enrollment = $email = $mobile = $course = "";
$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username   = trim(mysqli_real_escape_string($conn, $_POST["username"] ?? ""));
    $fullname   = trim(mysqli_real_escape_string($conn, $_POST["fullname"] ?? ""));
    $enrollment = trim(mysqli_real_escape_string($conn, $_POST["enrollment"] ?? ""));
    $email      = trim(mysqli_real_escape_string($conn, $_POST["email"] ?? ""));
    $mobile     = trim(mysqli_real_escape_string($conn, $_POST["mobile"] ?? ""));
    $course     = trim(mysqli_real_escape_string($conn, $_POST["course"] ?? ""));
    $password   = $_POST["password"] ?? "";
    $confirmPass = $_POST["confirm_password"] ?? "";

    if (empty($username) || strlen($username) < 4) $errors[] = "Username must be at least 4 characters.";
    if (empty($fullname)) $errors[] = "Full Name is required.";
    if (empty($enrollment)) $errors[] = "Enrollment Number is required.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
    if (empty($mobile) || !preg_match("/^[6-9]\d{9}$/", $mobile)) $errors[] = "Mobile must be 10 digits starting with 6-9.";
    if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";
    if ($password !== $confirmPass) $errors[] = "Passwords do not match.";

    // Duplicate Check
    if (empty($errors)) {
        $chk = mysqli_query($conn, "SELECT id, email, username FROM users WHERE email = '$email' OR username = '$username' LIMIT 1");
        if ($chk && mysqli_num_rows($chk) > 0) {
            $errors[] = "An account with that email or username already exists.";
        }
    }

    // Insert
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $userSql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed', 'student')";

        if (mysqli_query($conn, $userSql)) {
            $newUid = mysqli_insert_id($conn);
            $studSql = "INSERT INTO students (user_id, enrollment_no, full_name, email, mobile, course, semester) 
                        VALUES ($newUid, '$enrollment', '$fullname', '$email', '$mobile', '$course', 3)";
            mysqli_query($conn, $studSql);

            // Audit log
            log_system_action($conn, $newUid, "USER_REGISTER", "New student registered: $username ($enrollment)");

            $successMessage = "Account registered successfully for $fullname! You may now sign in.";
            $username = $fullname = $enrollment = $email = $mobile = $course = "";
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
        }
    }
}
?>

<div class="card" style="max-width: 600px; margin: 0 auto;">
  <h2>Student Registration</h2>
  <p style="color: #64748b; font-size: 13px; margin-bottom: 20px;">Create an account to register for events and manage your student profile.</p>

  <?php if (!empty($successMessage)): ?>
    <div class="alert alert-success">
      ✅ <?php echo htmlspecialchars($successMessage); ?><br><br>
      <a href="login.php" style="color: #166534; font-weight: bold; text-decoration: underline;">Click here to Login &rarr;</a>
    </div>
  <?php endif; ?>

  <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
      <strong>Registration errors:</strong>
      <ul style="padding-left: 20px; margin-top: 5px;">
        <?php foreach ($errors as $e): ?>
          <li><?php echo htmlspecialchars($e); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <form action="register.php" method="POST">
    <div class="form-group">
      <label for="username">Username (min. 4 chars): *</label>
      <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
    </div>

    <div class="form-group">
      <label for="fullname">Student Full Name: *</label>
      <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
    </div>

    <div class="form-group">
      <label for="enrollment">Enrollment Number: *</label>
      <input type="text" id="enrollment" name="enrollment" value="<?php echo htmlspecialchars($enrollment); ?>" placeholder="e.g. 24IT099" required>
    </div>

    <div class="form-group">
      <label for="email">College Email: *</label>
      <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="student@charusat.edu.in" required>
    </div>

    <div class="form-group">
      <label for="mobile">Mobile Number (10 Digits): *</label>
      <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" placeholder="9876543210" required>
    </div>

    <div class="form-group">
      <label for="course">Course / Department: *</label>
      <select id="course" name="course" required>
        <option value="B.Tech IT">B.Tech IT</option>
        <option value="B.Tech CSE">B.Tech CSE</option>
        <option value="B.Tech CE">B.Tech CE</option>
        <option value="BCA">BCA</option>
        <option value="MCA">MCA</option>
      </select>
    </div>

    <div class="form-group">
      <label for="password">Password (min. 6 chars): *</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
      <label for="confirm_password">Confirm Password: *</label>
      <input type="password" id="confirm_password" name="confirm_password" required>
    </div>

    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; font-size: 15px;">Complete Registration</button>
  </form>

  <p style="text-align: center; margin-top: 15px; font-size: 13px; color: #64748b;">
    Already registered? <a href="login.php" style="color: #0056b3; font-weight: bold;">Log in here</a>
  </p>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
