<?php
include '../db/db.php'; // Ensure proper database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    $request_id = intval($_POST['request_id']); // Secure integer conversion

    // Start database transaction for safe deletion
    $conn->begin_transaction();

    // Delete related medical certificate from cash_documents table
    $stmt1 = $conn->prepare("DELETE FROM cash_documents WHERE request_id = ?");
    $stmt1->bind_param("i", $request_id);
    if (!$stmt1->execute()) {
        $conn->rollback();
        die("Error deleting document: " . $conn->error);
    }
    $stmt1->close();

    // Delete the request itself from cash_requests table
    $stmt2 = $conn->prepare("DELETE FROM cash_requests WHERE id = ?");
    $stmt2->bind_param("i", $request_id);
    if (!$stmt2->execute()) {
        $conn->rollback();
        die("Error deleting request: " . $conn->error);
    }
    $stmt2->close();

    // Commit changes if both deletions succeed
    $conn->commit();

    // Redirect back to the management page with a success message
    header("Location: manage_cash_donations.php?status=deleted");
    exit();
} else {
    die("Invalid request.");
}
?>
