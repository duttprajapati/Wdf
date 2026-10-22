<?php
// Practical 15: Centralized Audit Logger

function log_system_action($conn, $userId, $action, $details = "") {
    $uid     = $userId ? intval($userId) : "NULL";
    $actEsc  = mysqli_real_escape_string($conn, $action);
    $detEsc  = mysqli_real_escape_string($conn, $details);
    $ip      = $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1";

    $sql = "INSERT INTO audit_logs (user_id, action, details, ip_address) 
            VALUES ($uid, '$actEsc', '$detEsc', '$ip')";
    
    return mysqli_query($conn, $sql);
}
?>
