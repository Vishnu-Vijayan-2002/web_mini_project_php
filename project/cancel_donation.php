<?php
include './db/db.php';

$donationId = isset($_GET['donation_id']) ? (int) $_GET['donation_id'] : 0;

if ($donationId <= 0) {
    echo "Invalid request.";
    exit;
}

// Get donation details
$sql = "SELECT user_id, donation_status FROM donations WHERE id = $donationId";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    $userId = $row['user_id'];
    $status = $row['donation_status'];

    if ($status !== 'Cancelled') {
        $updateSql = "UPDATE donations SET donation_status = 'Cancelled' WHERE id = $donationId";
        if (mysqli_query($conn, $updateSql)) {
            header("Location: view_donation_status.php?user_id=$userId");
            exit;
        } else {
            echo "Error cancelling donation: " . mysqli_error($conn);
        }
    } else {
        echo "This donation is already cancelled.";
    }
} else {
    echo "Donation not found.";
}
?>
