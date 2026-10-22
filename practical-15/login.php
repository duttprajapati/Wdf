<?php
$pageTitle = "Login";
$activeNav = "login";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/logger.php";

if ($isLoggedIn) {
    if ($userRole === "admin") {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: student_dashboard.php");
    }
    exit;
}

$errorMessage = "";
$statusMessage = "";

if (isset($_GET["msg"])) {
    if ($_GET["msg"] === "logout") $statusMessage = "You have safely logged out of StudentHub.";
    if ($_GET["msg"] === "timeout") $errorMessage = "Your session expired due to inactivity. Please log in again.";
    if ($_GET["msg"] === "unauthorized") $errorMessage = "Please log in to access this page.";
    if ($_GET["msg"] === "forbidden") $errorMessage = "Access denied: Insufficient privileges.";
}

$remembered = $_COOKIE["studenthub_remember"] ?? "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim(mysqli_real_escape_string($conn, $_POST["identifier"] ?? ""));
    $password   = $_POST["password"] ?? "";
    $rememberMe = isset($_POST["remember_me"]);

    if (empty($identifier) || empty($password)) {
        $errorMessage = "Please enter both your credentials.";
    } else {
        $sql = "SELECT id, username, email, password, role FROM users WHERE username = '$identifier' OR email = '$identifier' LIMIT 1";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) === 1) {
            $user = mysqli_fetch_assoc($res);

            if (password_verify($password, $user["password"])) {
                session_regenerate_id(true);

                $_SESSION["logged_in"] = true;
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["user_role"] = $user["role"];
                $_SESSION["last_activity"] = time();

                // Remember me cookie
                if ($rememberMe) {
                    setcookie("studenthub_remember", $user["username"], time() + (86400 * 7), "/");
                } else {
                    setcookie("studenthub_remember", "", time() - 3600, "/");
                }

                // Audit log
                log_system_action($conn, $user["id"], "LOGIN_SUCCESS", "User logged in with role: " . $user["role"]);

                if ($user["role"] === "admin") {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: student_dashboard.php");
                }
                exit;

            } else {
                $errorMessage = "Invalid password. Please try again.";
            }
        } else {
            $errorMessage = "No registered user found with that username or email.";
        }
    }
}
?>

<div class="card" style="max-width: 450px; margin: 30px auto;">
  <h2 style="text-align: center;">Sign In to StudentHub</h2>
  <p style="text-align: center; color: #64748b; font-size: 13px; margin-bottom: 20px;">Secure Session &amp; Role-Based Authentication</p>

  <?php if (!empty($statusMessage)): ?>
    <div class="alert alert-success">✅ <?php echo htmlspecialchars($statusMessage); ?></div>
  <?php endif; ?>

  <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger">⚠️ <?php echo htmlspecialchars($errorMessage); ?></div>
  <?php endif; ?>

  <form action="login.php" method="POST">
    <div class="form-group">
      <label for="identifier">Username or College Email:</label>
      <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($remembered); ?>" required>
    </div>

    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
      <input type="checkbox" id="remember_me" name="remember_me" <?php if (!empty($remembered)) echo "checked"; ?>>
      <label for="remember_me" style="margin-bottom: 0; font-weight: normal; font-size: 13px; cursor: pointer;">Remember my username</label>
    </div>

    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; font-size: 15px;">Sign In</button>
  </form>

  <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px; border-radius: 4px; margin-top: 20px; font-size: 12px; color: #475569;">
    <strong>Demo Credentials:</strong><br>
    &bull; <strong>Admin:</strong> <code>admin</code> / <code>password123</code><br>
    &bull; <strong>Student:</strong> <code>rahul24</code> / <code>password123</code>
  </div>

  <p style="text-align: center; margin-top: 15px; font-size: 13px;">
    Don't have an account? <a href="register.php" style="color: #0056b3; font-weight: bold;">Register here</a>
  </p>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
