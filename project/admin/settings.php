<?php
include '../db/db.php';

$updateMsg = "";

// Get admin email from POST (since we're not using session/cookies)
$adminEmail = $_POST['admin_email'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($adminEmail)) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($password !== "") {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name=?, email=?, password=? WHERE email=? AND user_type='admin'");
        $stmt->bind_param("ssss", $name, $email, $hashed, $adminEmail);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE email=? AND user_type='admin'");
        $stmt->bind_param("sss", $name, $email, $adminEmail);
    }

    if ($stmt->execute()) {
        $updateMsg = "Profile updated successfully!";
    } else {
        $updateMsg = "Update failed. Try again.";
    }
}

// Fetch admin details if email is provided
$admin = null;
if (!empty($adminEmail)) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND user_type='admin'");
    $stmt->bind_param("s", $adminEmail);
    $stmt->execute();
    $admin = $stmt->get_result()->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { overflow-x: hidden; }
        .main-content { margin-left: 240px; padding: 20px; max-width: 600px; }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h2 class="mb-4">Settings</h2>

    <?php if ($updateMsg): ?>
        <div class="alert alert-info"><?= $updateMsg ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return injectEmailToForm()">
        <input type="hidden" id="admin_email" name="admin_email" value="">

        <div class="mb-3">
            <label class="form-label">Name:</label>
            <input value="<?= htmlspecialchars($admin['name'] ?? '') ?>" type="text" class="form-control" name="name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input value="<?= htmlspecialchars($admin['email'] ?? '') ?>" type="email" class="form-control" name="email" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password (leave blank to keep current):</label>
            <input type="password" class="form-control" name="password">
        </div>

        <button type="submit" class="btn btn-primary">Update Settings</button>
    </form>
</div>

<script>
    // Frontend access control using localStorage
    const userType = localStorage.getItem('user_type');

    if (userType !== 'admin') {
        alert("Access denied!");
        window.location.href = '../index.php';
    }
</script>

</body>
</html>
