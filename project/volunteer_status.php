<?php
include './db/db.php';

$status = '';
$name = '';

// Get email from query string (or you can use session/localStorage logic)
if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $stmt = $conn->prepare("SELECT name, status FROM volunteers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($name, $status);
    
    if (!$stmt->fetch()) {
        $status = 'not_found';
    }

    $stmt->close();
    $conn->close();
} else {
    $status = 'invalid';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <link rel="stylesheet" href="./volunteer_status.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<section class="status-container">

    <?php if ($status === 'pending'): ?>
        <div class="status-card waiting">
            <div class="loader"></div>
            <h2>Hello <?= htmlspecialchars($name) ?>, Your Application is Under Review</h2>
            <p>Thank you for applying! We’re reviewing your application. You’ll be notified via email soon.</p>
            <span class="status-label">⏳ Pending Approval</span>
            <a href="index.php" class="btn home-btn">Back to Home</a>
        </div>

    <?php elseif ($status === 'approved'): ?>
        <div class="status-card approved">
            <div class="approved-animation">
                <i class="fa-solid fa-check tick-icon"></i>
            </div>
            <h2>Congratulations <?= htmlspecialchars($name) ?>!</h2>
            <p>Your application has been approved. You can now log in and start contributing.</p>
            <span class="status-label">✅ Approved</span>
            <a href="volunteer_login.php" class="btn login-btn">Log In</a>
        </div>

    <?php elseif ($status === 'not_found'): ?>
        <div class="status-card error">
            <h2>Email Not Found</h2>
            <p>No application was found for the provided email. Please check and try again.</p>
            <a href="volunteer_register.php" class="btn retry-btn">Register Again</a>
        </div>

    <?php elseif ($status === 'invalid'): ?>
        <div class="status-card error">
            <h2>Invalid Request</h2>
            <p>No email provided. Please register or check your email link.</p>
            <a href="volunteer_register.php" class="btn retry-btn">Go to Registration</a>
        </div>
    <?php endif; ?>

</section>

</body>
</html>
