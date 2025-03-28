<?php
// Dummy status for testing - Replace with real database query
$status = 'pending';  // Options: 'pending' or 'approved'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./volunteer_status.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Application Status</title>
</head>
<body>

<section class="status-container">

    <?php if ($status === 'pending'): ?>
        <!-- Waiting with Animation -->
        <div class="status-card waiting">
            <div class="loader"></div>   <!-- Animated Loader -->
            <h2>Your Application is Under Review</h2>
            <p>Thank you for applying! Your application is currently being reviewed by our admin team. 
               You will be notified via email once approved.</p>
            <span class="status-label">⏳ Pending Approval</span>
            <a href="index.php" class="btn home-btn">Back to Home</a>
        </div>

    <?php elseif ($status === 'approved'): ?>
        <!-- Approved Section with Animated Tick -->
        <div class="status-card approved">
            <div class="approved-animation">
                <i class="fa-solid fa-check tick-icon"></i>   <!-- Font Awesome Tick -->
            </div>

            <h2>Application Approved!</h2>
            <p>Congratulations! Your application has been approved. You can now log in and start contributing.</p>
            <span class="status-label">✅ Approved</span>
            <a href="volunteer_login.php" class="btn login-btn">Log In</a>
        </div>
    <?php endif; ?>

</section>

</body>
</html>
