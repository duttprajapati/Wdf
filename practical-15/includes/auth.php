<?php
// Practical 15: Session Guard & RBAC Helpers

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 10-Minute Inactivity Session Timeout
define("AUTH_TIMEOUT", 600);

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"] > AUTH_TIMEOUT)) {
        session_unset();
        session_destroy();
        header("Location: login.php?msg=timeout");
        exit;
    }
    $_SESSION["last_activity"] = time();
}

function require_login() {
    if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
        header("Location: login.php?msg=unauthorized");
        exit;
    }
}

function require_admin() {
    require_login();
    if ($_SESSION["user_role"] !== "admin") {
        header("Location: login.php?msg=forbidden");
        exit;
    }
}

function require_student() {
    require_login();
    if ($_SESSION["user_role"] !== "student") {
        header("Location: login.php?msg=forbidden");
        exit;
    }
}
?>
