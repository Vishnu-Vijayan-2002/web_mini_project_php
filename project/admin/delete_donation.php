<?php
include '../db/db.php';

// Check if ID is passed and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $donationId = $_GET['id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM donations WHERE id = ?");
    $stmt->bind_param("i", $donationId);

    if ($stmt->execute()) {
        // Redirect back with success message
        header("Location: manage_donations.php?msg=Donation deleted successfully");
        exit();
    } else {
        // Error deleting
        header("Location: manage_donations.php?msg=Failed to delete donation");
        exit();
    }

} else {
    // Invalid ID
    header("Location: manage_donations.php?msg=Invalid donation ID");
    exit();
}
?>
