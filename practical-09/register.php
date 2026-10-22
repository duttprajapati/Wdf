<?php
// Practical 9: Secure User Registration with MySQL Insert & Duplicate Checks
require_once __DIR__ . "/db.php";

$username = $fullname = $enrollment = $email = $mobile = $course = "";
$errors = [];
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Sanitize Inputs
    $username   = trim(mysqli_real_escape_string($conn, $_POST["username"] ?? ""));
    $fullname   = trim(mysqli_real_escape_string($conn, $_POST["fullname"] ?? ""));
    $enrollment = trim(mysqli_real_escape_string($conn, $_POST["enrollment"] ?? ""));
    $email      = trim(mysqli_real_escape_string($conn, $_POST["email"] ?? ""));
    $mobile     = trim(mysqli_real_escape_string($conn, $_POST["mobile"] ?? ""));
    $course     = trim(mysqli_real_escape_string($conn, $_POST["course"] ?? ""));
    $password   = $_POST["password"] ?? "";
    $confirmPass = $_POST["confirm_password"] ?? "";

    // 2. Input Validations
    if (empty($username) || strlen($username) < 4) {
        $errors[] = "Username must be at least 4 characters long.";
    }

    if (empty($fullname)) {
        $errors[] = "Full Name is required.";
    }

    if (empty($enrollment)) {
        $errors[] = "Enrollment Number is required.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid college email address is required.";
    }

    if (empty($mobile) || !preg_match("/^[6-9]\d{9}$/", $mobile)) {
        $errors[] = "Mobile number must be 10 digits starting with 6, 7, 8, or 9.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirmPass) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    // 3. Duplicate Email / Username Check
    if (empty($errors)) {
        $checkSql = "SELECT id, email, username FROM users WHERE email = '$email' OR username = '$username' LIMIT 1";
        $checkResult = mysqli_query($conn, $checkSql);

        if ($checkResult && mysqli_num_rows($checkResult) > 0) {
            $existing = mysqli_fetch_assoc($checkResult);
            if ($existing["email"] === $email) {
                $errors[] = "An account with this email address already exists. Please login instead.";
            } else {
                $errors[] = "This username is already taken. Please choose another username.";
            }
        }
    }

    // 4. Secure Password Hashing & Database Insert
    if (empty($errors)) {
        // Hash password securely using PHP's native bcrypt/Argon2
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert into users table
        $userSql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashedPassword', 'student')";
        
        if (mysqli_query($conn, $userSql)) {
            $newUserId = mysqli_insert_id($conn);

            // Insert into students table
            $studentSql = "INSERT INTO students (user_id, enrollment_no, full_name, email, mobile, course, semester) 
                           VALUES ('$newUserId', '$enrollment', '$fullname', '$email', '$mobile', '$course', 3)";
            mysqli_query($conn, $studentSql);

            $successMessage = "Account created successfully for '$fullname'! You can now log in.";
            // Reset input values
            $username = $fullname = $enrollment = $email = $mobile = $course = "";
        } else {
            $errors[] = "Database insert error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - Secure Registration (Practical 9)</title>
  <link rel="stylesheet" href="css/style.css">
  <script>
    // Client-side validation helper
    function validateClientForm() {
      const pass = document.getElementById("password").value;
      const cpass = document.getElementById("confirm_password").value;
      if (pass !== cpass) {
        alert("Client Check: Passwords do not match!");
        return false;
      }
      return true;
    }
  </script>
</head>
<body>

  <div class="auth-container">
    <div class="auth-header">
      <h1>StudentHub Registration</h1>
      <p>Practical 9: Secure Registration with Database Insert &amp; Duplicate Checks</p>
    </div>

    <?php if (!empty($successMessage)): ?>
      <div class="alert-success">
        ✅ <?php echo $successMessage; ?>
        <br><br>
        <a href="login.php" style="color: #166534; font-weight: bold; text-decoration: underline;">Proceed to Student Login &rarr;</a>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="alert-danger">
        <strong>Registration failed:</strong>
        <ul>
          <?php foreach ($errors as $err): ?>
            <li><?php echo htmlspecialchars($err); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form action="register.php" method="POST" onsubmit="return validateClientForm();">

      <div class="form-group">
        <label for="username">Username (min. 4 chars): *</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
      </div>

      <div class="form-group">
        <label for="fullname">Full Name: *</label>
        <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required>
      </div>

      <div class="form-group">
        <label for="enrollment">Enrollment Number: *</label>
        <input type="text" id="enrollment" name="enrollment" value="<?php echo htmlspecialchars($enrollment); ?>" placeholder="e.g. 24IT050" required>
      </div>

      <div class="form-group">
        <label for="email">College Email: *</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="e.g. student@charusat.edu.in" required>
      </div>

      <div class="form-group">
        <label for="mobile">Mobile Number: *</label>
        <input type="tel" id="mobile" name="mobile" value="<?php echo htmlspecialchars($mobile); ?>" placeholder="10-digit mobile" required>
      </div>

      <div class="form-group">
        <label for="course">Program / Department: *</label>
        <select id="course" name="course" required>
          <option value="B.Tech IT" <?php if ($course === "B.Tech IT") echo "selected"; ?>>B.Tech IT</option>
          <option value="B.Tech CSE" <?php if ($course === "B.Tech CSE") echo "selected"; ?>>B.Tech CSE</option>
          <option value="BCA" <?php if ($course === "BCA") echo "selected"; ?>>BCA</option>
          <option value="MCA" <?php if ($course === "MCA") echo "selected"; ?>>MCA</option>
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

      <button type="submit" class="btn-submit">Register Account</button>
    </form>

    <div class="auth-footer">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>

</body>
</html>
