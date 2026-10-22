<?php
// Practical 11: Delete Student Record
require_once __DIR__ . "/db.php";

$id = intval($_GET["id"] ?? 0);

if ($id > 0) {
    // Delete record from students table
    $delSql = "DELETE FROM students WHERE id = $id";
    if (mysqli_query($conn, $delSql)) {
        header("Location: index.php?msg=deleted");
        exit;
    } else {
        header("Location: index.php?msg=error");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
