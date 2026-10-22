<?php
// Practical 10: Secure Login Processing with Role-Based Redirection & Remember-Me
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/auth_check.php";

// If already logged in, redirect directly to dashboard
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    if ($_SESSION["user_role"] === "admin") {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }
    exit;
}

$errorMessage = "";
$statusMessage = "";

// Check URL query parameters for feedback
if (isset($_GET["msg"])) {
    if ($_GET["msg"] === "logout") {
        $statusMessage = "You have been successfully logged out.";
    } elseif ($_GET["msg"] === "timeout") {
        $errorMessage = "Your session expired due to inactivity. Please log in again.";
    } elseif ($_GET["msg"] === "unauthorized") {
        $errorMessage = "You must log in first to access the dashboard.";
    } elseif ($_GET["msg"] === "forbidden") {
        $errorMessage = "Access denied: You do not have permission to view that page.";
    }
}

// Check for "Remember Me" Cookie to prefill username
$rememberedUser = isset($_COOKIE["studenthub_remember"]) ? $_COOKIE["studenthub_remember"] : "";

// Process Login Form Submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim(mysqli_real_escape_string($conn, $_POST["identifier"] ?? ""));
    $password   = $_POST["password"] ?? "";
    $rememberMe = isset($_POST["remember_me"]);

    if (empty($identifier) || empty($password)) {
        $errorMessage = "Both username/email and password are required.";
    } else {
        // Query user by username or email
        $sql = "SELECT id, username, email, password, role FROM users WHERE username = '$identifier' OR email = '$identifier' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            // Verify hashed password
            if (password_verify($password, $user["password"])) {
                
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                // Set session variables
                $_SESSION["logged_in"] = true;
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["user_role"] = $user["role"];
                $_SESSION["last_activity"] = time();

                // Handle Remember-Me Cookie (valid for 7 days)
                if ($rememberMe) {
                    setcookie("studenthub_remember", $user["username"], time() + (86400 * 7), "/");
                } else {
                    // Clear cookie if unchecked
                    setcookie("studenthub_remember", "", time() - 3600, "/");
                }

                // Role-based redirection
                if ($user["role"] === "admin") {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: student_dashboard.php");
                }
                exit;

            } else {
                $errorMessage = "Invalid credentials. Incorrect password.";
            }
        } else {
            $errorMessage = "No registered user found with that username or email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>StudentHub - Secure Login (Practical 10)</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <div class="login-box">
    <h1 style="color: #003366; font-size: 22px; text-align: center; margin-bottom: 5px;">StudentHub Portal</h1>
    <p style="text-align: center; color: #64748b; font-size: 13px; margin-bottom: 20px;">Practical 10: Secure Session &amp; Role-Based Access</p>

    <?php if (!empty($statusMessage)): ?>
      <div class="alert alert-success">✅ <?php echo htmlspecialchars($statusMessage); ?></div>
    <?php endif; ?>

    <?php if (!empty($errorMessage)): ?>
      <div class="alert alert-danger">⚠️ <?php echo htmlspecialchars($errorMessage); ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="identifier">Username or College Email:</label>
        <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($rememberedUser); ?>" placeholder="e.g. admin or rahul24" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="e.g. password123" required>
      </div>

      <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
        <input type="checkbox" id="remember_me" name="remember_me" <?php if (!empty($rememberedUser)) echo "checked"; ?>>
        <label for="remember_me" style="margin-bottom: 0; font-weight: normal; font-size: 13px; cursor: pointer;">Remember my username</label>
      </div>

      <button type="submit" class="btn-primary">Sign In to Dashboard</button>
    </form>

    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px; border-radius: 4px; margin-top: 20px; font-size: 12px; color: #475569;">
      <strong>Test Accounts Available:</strong><br>
      &bull; <strong>Admin:</strong> Username: <code>admin</code> | Password: <code>password123</code><br>
      &bull; <strong>Student:</strong> Username: <code>rahul24</code> | Password: <code>password123</code>
    </div>
  </div>

</body>
</html>
