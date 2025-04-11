<?php
session_start();
header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['user_email'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

include './db/db.php';

// Query donations
$sql = "SELECT id, food_type, quantity, location, donor, donor_status, status FROM donations";
$result = $conn->query($sql);

$donations = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $donations[] = [
            'id' => $row['id'],
            'foodType' => $row['food_type'],
            'quantity' => $row['quantity'],
            'location' => $row['location'],
            'donor' => $row['donor'],
            'donorStatus' => $row['donor_status'],
            'status' => $row['status']
        ];
    }
}

$conn->close();
echo json_encode($donations);
?>
