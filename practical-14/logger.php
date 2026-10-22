<?php
// Practical 14: Audit Logging Helper Function

function record_audit_log($conn, $userId, $action, $details = "") {
    $userIdVal  = $userId ? intval($userId) : "NULL";
    $actionEsc  = mysqli_real_escape_string($conn, $action);
    $detailsEsc = mysqli_real_escape_string($conn, $details);
    $ipAddress  = $_SERVER["REMOTE_ADDR"] ?? "127.0.0.1";

    $sql = "INSERT INTO audit_logs (user_id, action, details, ip_address) 
            VALUES ($userIdVal, '$actionEsc', '$detailsEsc', '$ipAddress')";
    
    return mysqli_query($conn, $sql);
}
?>
