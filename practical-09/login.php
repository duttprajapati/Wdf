<?php
// Practical 9: Companion Login Test Page
require_once __DIR__ . "/db.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userOrEmail = trim(mysqli_real_escape_string($conn, $_POST["user_email"] ?? ""));
    $password = $_POST["password"] ?? "";

    $sql = "SELECT id, username, email, password, role FROM users WHERE username = '$userOrEmail' OR email = '$userOrEmail' LIMIT 1";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) === 1) {
        $row = mysqli_fetch_assoc($res);
        // Verify hashed password
        if (password_verify($password, $row["password"])) {
            $message = "Login verification successful! Welcome " . htmlspecialchars($row["username"]) . " (Role: " . htmlspecialchars($row["role"]) . ").";
            $messageType = "success";
        } else {
            $message = "Invalid password. Please check your credentials.";
            $messageType = "danger";
        }
    } else {
        $message = "No account found with this username or email.";
        $messageType = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>StudentHub - Login Test (Practical 9)</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="auth-container">
    <div class="auth-header">
      <h1>StudentHub Login Test</h1>
      <p>Verify registration credentials with password_verify()</p>
    </div>

    <?php if (!empty($message)): ?>
      <div class="alert-<?php echo $messageType; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="user_email">Username or College Email:</label>
        <input type="text" id="user_email" name="user_email" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="btn-submit">Sign In</button>
    </form>

    <div class="auth-footer">
      Need an account? <a href="register.php">Register here</a>
    </div>
  </div>

</body>
</html>
