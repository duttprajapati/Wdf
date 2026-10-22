<?php
// Practical 10: Session Security, Inactivity Timeout & Role Protection Helper

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Session Inactivity Timeout Configuration (e.g. 10 minutes = 600 seconds)
define("SESSION_TIMEOUT_SECONDS", 600);

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    if (isset($_SESSION["last_activity"])) {
        $inactiveSeconds = time() - $_SESSION["last_activity"];
        if ($inactiveSeconds > SESSION_TIMEOUT_SECONDS) {
            // Session has expired due to inactivity
            session_unset();
            session_destroy();
            header("Location: login.php?msg=timeout");
            exit;
        }
    }
    // Update last active timestamp
    $_SESSION["last_activity"] = time();
}

// 2. Helper Function to Restrict Access Based on Role
function require_role($requiredRole) {
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: login.php?msg=unauthorized");
        exit;
    }

    if ($_SESSION["user_role"] !== $requiredRole) {
        // Logged in, but wrong role (e.g., student trying to access admin dashboard)
        header("Location: login.php?msg=forbidden");
        exit;
    }
}
?>
