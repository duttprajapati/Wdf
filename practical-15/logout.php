<?php
// Practical 15: Secure Logout
session_start();
require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/logger.php";

if (isset($_SESSION["user_id"])) {
    log_system_action($conn, $_SESSION["user_id"], "LOGOUT", "User logged out safely");
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: login.php?msg=logout");
exit;
?>
