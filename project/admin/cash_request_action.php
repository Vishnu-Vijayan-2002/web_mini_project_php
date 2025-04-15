<?php
include '../db/db.php'; // Ensure proper database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'])) {
    $request_id = intval($_POST['request_id']); // Security measure

    // Handle Approve, Reject, or Delete action
    if (isset($_POST['approve'])) {
        $status = 'approved';
        $timestamp = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("UPDATE cash_requests SET status = ?, approved_at = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $timestamp, $request_id);
        $stmt->execute();
        $stmt->close();

        header("Location: manage_cash_donations.php?status=success");
        exit();

    } elseif (isset($_POST['reject'])) {
        $status = 'rejected';
        $timestamp = date("Y-m-d H:i:s");

        $stmt = $conn->prepare("UPDATE cash_requests SET status = ?, approved_at = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $timestamp, $request_id);
        $stmt->execute();
        $stmt->close();

        header("Location: manage_cash_donations.php?status=success");
        exit();

    } elseif (isset($_POST['delete'])) {
        $conn->begin_transaction();

        $stmt1 = $conn->prepare("DELETE FROM cash_documents WHERE request_id = ?");
        $stmt1->bind_param("i", $request_id);
        if (!$stmt1->execute()) {
            $conn->rollback();
            die("Error deleting document: " . $conn->error);
        }
        $stmt1->close();

        $stmt2 = $conn->prepare("DELETE FROM cash_requests WHERE id = ?");
        $stmt2->bind_param("i", $request_id);
        if (!$stmt2->execute()) {
            $conn->rollback();
            die("Error deleting request: " . $conn->error);
        }
        $stmt2->close();

        $conn->commit();
        header("Location: manage_cash_donations.php?status=deleted");
        exit();
    } else {
        die("Invalid action.");
    }
} else {
    die("Invalid request.");
}
?>
