<?php
include './db/db.php'; 

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Check if the token is valid and not expired
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE token=? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows === 1) {
        $resetRequest = $result->fetch_assoc();

        // If form submitted
        if (isset($_POST['reset_password'])) {
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword !== $confirmPassword) {
                echo "Passwords do not match!";
            } else {
                // NO hashing here — just plain password
                $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
                $update->bind_param("si", $newPassword, $resetRequest['user_id']);
                $update->execute();

                // Delete used reset token
                $delete = $conn->prepare("DELETE FROM password_resets WHERE token=?");
                $delete->bind_param("s", $token);
                $delete->execute();

                echo "✅ Password has been reset successfully!";
            }
        }
    } else {
        echo "❌ Invalid or expired token!";
    }
} else {
    echo "❌ Token not provided!";
}
?>

<!-- Password Reset Form -->
<form method="POST">
    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" placeholder="Enter new password" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
    </div>
    <button type="submit" name="reset_password" class="btn btn-success w-100">Reset Password</button>
</form>
