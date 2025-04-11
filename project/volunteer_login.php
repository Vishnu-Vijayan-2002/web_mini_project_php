<?php
session_start();
include './db/db.php'; // Update the path if needed

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $name, $stored_password);
            $stmt->fetch();

            // NOTE: In production, use password_verify() with hashed passwords
            if ($password === $stored_password) {
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email; // Save email to session
                header("Location: volunteer_index.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with this email.";
        }

        $stmt->close();
    } else {
        $error = "Please enter both email and password.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card p-5 shadow-lg" style="width: 400px;">
        <h3 class="text-center mb-4">Volunteer Login</h3>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-sign-in-alt"></i> Login
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</div>

</body>
</html>
