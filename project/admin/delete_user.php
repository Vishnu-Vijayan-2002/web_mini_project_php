<?php
include '../db/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_users.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
} else {
    header("Location: manage_users.php");
    exit();
}
