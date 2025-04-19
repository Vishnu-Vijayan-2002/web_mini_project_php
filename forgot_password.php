<?php
require_once 'includes/db_connect.php'; // Ensure session started and DB connected
require_once __DIR__ . '/includes/send_mail.php'; // Use central mail helper

$errors = [];
$success_message = '';
$submitted_email = ''; // To repopulate the form field on error

function sendPasswordResetEmail($toEmail, $toName, $resetLink) {
    $subject = 'Password Reset Request - FoodShare Connect';
    $body = '<p>Hello ' . htmlspecialchars($toName) . ',</p>' .
        '<p>You requested a password reset. Click the link below to set a new password:</p>' .
        '<p><a href="' . htmlspecialchars($resetLink) . '">' . htmlspecialchars($resetLink) . '</a></p>' .
        '<p>This link will expire in 1 hour.</p>' .
        '<p>If you did not request this, please ignore this email.</p>' .
        '<p>Regards,<br>The FoodShare Team</p>';
    $altBody = "Hello $toName,\nYou requested a password reset. Visit this link: $resetLink\nThis link will expire in 1 hour. If you did not request this, please ignore this email. Regards, The FoodShare Connect Team";
    return sendMail($toEmail, $toName, $subject, $body, $altBody);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_forgot_password'])) {
    $submitted_email = trim($_POST['email'] ?? '');

    if (empty($submitted_email) || !filter_var($submitted_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address.";
    } else {
        try {
            // Find the user by email
            $sql_find = "SELECT user_id, first_name, email FROM users WHERE email = :email";
            $stmt_find = $pdo->prepare($sql_find);
            $stmt_find->bindParam(':email', $submitted_email, PDO::PARAM_STR);
            $stmt_find->execute();
            $user = $stmt_find->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // User found, generate token and update database
                $token_bytes = random_bytes(32); // Generate a secure random token
                $token = bin2hex($token_bytes); // Convert to hex for use in URL
                $token_hash = password_hash($token, PASSWORD_DEFAULT); // Hash the token for storage

                // *** MODIFICATION START ***
                // Use MySQL's DATE_ADD and NOW() to calculate expiry directly in the database
                // This avoids PHP/MySQL timezone discrepancies
                $stmt_update = $pdo->prepare("UPDATE users SET
                                                  reset_token_hash = :token_hash,
                                                  reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR)
                                              WHERE user_id = :uid");
                $stmt_update->execute([
                    ':token_hash' => $token_hash,
                    // No ':expiry' parameter needed now, MySQL handles it
                    ':uid' => $user['user_id']
                ]);
                // *** MODIFICATION END ***

                // Construct the reset link (ensure the base URL is correct for your environment)
                // TODO: Replace 'http://localhost/FoodShare/' with your actual base URL if different
                $base_url = rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']), '/\\') ;
                $reset_link = $base_url . "/reset_password.php?token=" . $token; // Send the raw token in the link

                // Send the email
                if (sendPasswordResetEmail($user['email'], $user['first_name'], $reset_link)) {
                    $success_message = "A password reset link has been sent to " . htmlspecialchars($user['email']) . " (please check your spam folder if you don't see it).";
                    error_log("Password reset email initiated successfully for " . $user['email']);
                } else {
                    $errors[] = "There was an issue sending the password reset email. Please try again later or contact support.";
                     error_log("Failed to send password reset email for " . $user['email']);
                }

            } else {
                // User not found, show a generic message to avoid revealing which emails are registered
                // Log this case for admin awareness if needed
                error_log("Password reset requested for non-existent email: " . $submitted_email);
                $success_message = "If an account with that email address exists, a password reset link has been sent. Please check your inbox and spam folder.";
            }
        } catch (PDOException $e) {
            error_log("Forgot Password DB Error: " . $e->getMessage() . " for email: " . $submitted_email);
            $errors[] = "A database error occurred. Please try again later.";
        } catch (Exception $e) { // Catch potential errors from random_bytes or mailer
            error_log("Forgot Password General Error: " . $e->getMessage() . " for email: " . $submitted_email);
            $errors[] = "An unexpected error occurred while processing your request.";
        }
    }
}

require_once 'includes/header.php'; // Include Bootstrap header
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0"><i class="bi bi-key-fill me-2"></i>Forgot Password</h4>
                </div>
                <div class="card-body">
                    <p class="card-text">Enter the email address associated with your account. If the email exists in our system, we'll send you a link to reset your password.</p>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php foreach ($errors as $error): ?>
                                <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success_message): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success_message; // Success message already handles htmlspecialchars where needed ?>
                        </div>
                    <?php endif; ?>

                    <?php // Only show form if there's no success message (or if there were errors preventing success) ?>
                    <?php if (empty($success_message) || !empty($errors)): ?>
                    <form action="forgot_password.php" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control <?php echo (!empty($errors) && strpos(implode(' ', $errors), 'email') !== false) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($submitted_email); ?>" required aria-describedby="emailHelp">
                             <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                             <?php if (!empty($errors) && strpos(implode(' ', $errors), 'email') !== false): ?>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" name="submit_forgot_password" class="btn btn-primary w-100">Send Password Reset Link</button>
                    </form>
                    <?php endif; ?>

                    <div class="mt-3 text-center">
                         <a href="login.php">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; // Include Bootstrap footer ?>