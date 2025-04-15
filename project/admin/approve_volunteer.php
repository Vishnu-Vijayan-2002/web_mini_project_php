<?php
include '../db/db.php'; // Ensure path is correct

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Step 1: Approve the volunteer
    $stmt = $conn->prepare("UPDATE volunteers SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Step 2: Fetch volunteer details
    $stmt = $conn->prepare("SELECT name, email, phone FROM volunteers WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $volunteer = $result->fetch_assoc();

    if ($volunteer) {
        $name = $volunteer['name'];
        $email = $volunteer['email'];
        $phone = $volunteer['phone'];
        $user_type = 'volunteer';

        // Step 3: Generate password using first name + "123"
        $firstName = strtolower(explode(" ", trim($name))[0]);
        $rawPassword = $firstName . "123"; // Plain password

        // Step 4: Check if the email already exists
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows === 0) {
            // Step 5: Insert into users table
            $insertStmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("ssss", $name, $email, $rawPassword, $user_type);
            $insertStmt->execute();
        }
    }
}

header("Location: volunteer_requests.php");
exit;
?>
