<?php
include './db/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name         = trim($_POST['name']);
    $email        = trim($_POST['email']);
    $phone        = trim($_POST['phone']);
    $aadhaar      = trim($_POST['aadhaar']);
    $availability = $_POST['availability'];
    $authority    = $_POST['authority'];
    $authority_id = trim($_POST['authority_id']);
    $skills       = trim($_POST['skills']);

    // Check if email already exists
    $check = $conn->prepare("SELECT id FROM volunteers WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "An application with this email already exists.";
    } else {
        // Insert new volunteer
        $stmt = $conn->prepare("INSERT INTO volunteers 
            (name, email, phone, aadhaar, availability, authority, authority_id, skills) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            $error = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ssssssss", $name, $email, $phone, $aadhaar, $availability, $authority, $authority_id, $skills);
            if ($stmt->execute()) {
                echo "<script>
                    alert('Volunteer registration successful! Your application is under review.');
                    window.location.href='volunteer_status.php?email=" . urlencode($email) . "';
                </script>";
                exit;
            } else {
                $error = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    $check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./volunteer_register.css">
    <title>Volunteer Registration</title>
</head>
<body>

<section class="register-container">
    <div class="form-card">
        <h2>Volunteer Registration</h2>
        <p>Join us and make a difference by helping distribute meals to those in need.</p>

        <!-- Success or Error Message -->
        <?php if ($success): ?>
            <div style="color: green; margin-bottom: 10px;"><?= $success ?></div>
        <?php elseif ($error): ?>
            <div style="color: red; margin-bottom: 10px;"><?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" 
                       pattern="^[A-Za-z\s]{3,50}$" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" 
                       pattern="^[6-9]\d{9}$" required>
            </div>

            <div class="form-group">
                <label for="aadhaar">Aadhaar Number</label>
                <input type="text" id="aadhaar" name="aadhaar" placeholder="XXXX XXXX XXXX" 
                       pattern="^\d{4}\s\d{4}\s\d{4}$" required>
            </div>

            <div class="form-group">
                <label for="availability">Availability</label>
                <select id="availability" name="availability" required>
                    <option value="">-- Select --</option>
                    <option value="weekdays">Weekdays</option>
                    <option value="weekends">Weekends</option>
                    <option value="full-time">Full-time</option>
                </select>
            </div>

            <div class="form-group">
                <label for="authority">Volunteer Affiliation</label>
                <select id="authority" name="authority" required>
                    <option value="">-- Select Authority --</option>
                    <option value="NSS">NSS</option>
                    <option value="NCC">NCC</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="authority_id">Authority ID Number</label>
                <input type="text" id="authority_id" name="authority_id" placeholder="Enter your volunteer ID number" 
                       pattern="^[A-Za-z0-9\-]{3,20}$" required>
            </div>

            <div class="form-group">
                <label for="skills">Skills or Experience</label>
                <textarea id="skills" name="skills" placeholder="Mention relevant skills or experience" 
                          rows="4" minlength="10"></textarea>
            </div>

            <button type="submit" class="btn submit-btn">Register Now</button>
        </form>
        
        <a href="index.php" class="back-btn">← Back to Home</a>
    </div>
</section>

</body>
</html>
