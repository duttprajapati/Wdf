<?php
// Practical 12: Delete Event Record & Remove Poster Image File
require_once __DIR__ . "/db.php";

$id = intval($_GET["id"] ?? 0);

if ($id > 0) {
    // 1. Fetch poster filename to remove file from disk
    $res = mysqli_query($conn, "SELECT poster FROM events WHERE id = $id LIMIT 1");
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $posterFile = $row["poster"];

        if (!empty($posterFile) && file_exists(__DIR__ . "/uploads/" . $posterFile)) {
            unlink(__DIR__ . "/uploads/" . $posterFile);
        }
    }

    // 2. Delete record from database
    $delSql = "DELETE FROM events WHERE id = $id";
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
