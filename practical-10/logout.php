<?php
// Practical 10: Secure Logout Script
session_start();

// Unset all session variables
$_SESSION = [];

// Delete session cookie from client browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy session entirely
session_destroy();

// Redirect to login page with feedback message
header("Location: login.php?msg=logout");
exit;
?>
