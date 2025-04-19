<?php
require_once 'includes/db_connect.php'; // Ensure $pdo is available
require_once __DIR__ . '/includes/send_mail.php';

$errors = [];
$success_message = '';
$token_valid = false;
$user_id_to_reset = null;
$user_email = ''; // Store email/name for later use
$user_name = '';
$token_from_url_or_form = ''; // Use one variable

// --- Function to Validate Token ---
// Separating validation logic makes it reusable and cleaner
function validateResetToken(PDO $pdo, string $token): ?array {
    if (empty($token) || !ctype_xdigit($token) || strlen($token) !== 64) {
        return null; // Invalid format
    }

    try {
        // Fetch all potentially valid tokens (this is the standard approach when only storing the hash)
        $sql = "SELECT user_id, reset_token_hash, reset_token_expiry, email, first_name
                FROM users
                WHERE reset_token_expiry > NOW() AND reset_token_hash IS NOT NULL";
        $stmt = $pdo->query($sql); // Using query() is okay here as no user input

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Directly compare the provided token with the stored hash
            if (password_verify($token, $row['reset_token_hash'])) {
                // Match found! Return user data. Expiry already checked by SQL.
                return [
                    'user_id' => $row['user_id'],
                    'email' => $row['email'],
                    'first_name' => $row['first_name']
                ];
            }
        }
        // If loop finishes without a match
        return null;

    } catch (PDOException $e) {
        error_log("Token Validation DB Error: " . $e->getMessage());
        // Return null or throw an exception depending on how you want to handle DB errors
        return null;
    }
}


// --- Function to Send Confirmation ---
function sendPasswordResetConfirmationEmail($toEmail, $toName) {
    $subject = 'Your FoodShare password has been changed';
    $body = '<p>Hello ' . htmlspecialchars($toName) . ',</p>' .
            '<p>Your password was successfully changed. If you did not perform this action, please contact support immediately.</p>' .
            '<p>Regards,<br>FoodShare Team</p>';
    $altBody = "Hello $toName,\nYour password was successfully changed. If you did not perform this action, please contact support immediately.\nRegards,\nFoodShare Team"; // Corrected team name
    return sendMail($toEmail, $toName, $subject, $body, $altBody);
}

// --- Determine Token Source (GET or POST) ---
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
    $token_from_url_or_form = $_GET['token'];
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['token'])) {
    $token_from_url_or_form = $_POST['token'];
}

// --- Main Logic ---

if (!empty($token_from_url_or_form)) {
    $validation_result = validateResetToken($pdo, $token_from_url_or_form);

    if ($validation_result !== null) {
        // Token is valid (format checked, hash matched, not expired)
        $token_valid = true;
        $user_id_to_reset = $validation_result['user_id'];
        $user_email = $validation_result['email'];
        $user_name = $validation_result['first_name'];

        // --- Handle Form Submission (POST Request) ---
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_reset_password'])) {
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validate Passwords
            if (empty($password) || empty($confirm_password)) {
                $errors[] = "Both password fields are required.";
            } elseif ($password !== $confirm_password) {
                $errors[] = "Passwords do not match.";
            } elseif (strlen($password) < 6) { // Use a consistent minimum length (e.g., 8 or more is better)
                $errors[] = "Password must be at least 6 characters long.";
            }

            // If passwords are valid, update the database
            if (empty($errors)) {
                try {
                    $new_password_hash = password_hash($password, PASSWORD_DEFAULT);
                    $sql_update_pass = "UPDATE users SET password = :password, reset_token_hash = NULL, reset_token_expiry = NULL WHERE user_id = :user_id";
                    $stmt_update_pass = $pdo->prepare($sql_update_pass);
                    $stmt_update_pass->bindParam(':password', $new_password_hash, PDO::PARAM_STR);
                    $stmt_update_pass->bindParam(':user_id', $user_id_to_reset, PDO::PARAM_INT);

                    if ($stmt_update_pass->execute()) {
                        // Attempt to send confirmation email
                        if (sendPasswordResetConfirmationEmail($user_email, $user_name)) {
                             error_log("Password reset confirmation email sent successfully to " . $user_email);
                        } else {
                             error_log("Failed to send password reset confirmation email to " . $user_email);
                             // Don't show email failure to user, but log it.
                        }
                        $success_message = "Your password has been successfully updated! You can now log in with your new password.";
                        $token_valid = false; // Hide the form after success
                    } else {
                        $errors[] = "Failed to update password. Please try again.";
                        error_log("Failed to execute password update for user_id: " . $user_id_to_reset);
                    }
                } catch (PDOException $e) {
                    error_log("Reset Password Submit DB Error: " . $e->getMessage());
                    $errors[] = "A database error occurred. Please try again later.";
                } catch (Exception $e) { // Catch mailer exceptions specifically if sendMail throws them
                    error_log("Reset Password Email/General Error: " . $e->getMessage());
                    // Decide if you need to inform the user about email failure specifically
                    $errors[] = "An unexpected error occurred.";
                }
            }
            // If there were validation errors on POST, keep $token_valid = true to show the form again
             if (!empty($errors)) {
                 $token_valid = true;
             }

        } // End POST handling

    } else {
        // validateResetToken returned null
        if (!empty($token_from_url_or_form) && ($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST")) {
             // Only add the error if a token was actually provided but failed validation
             $errors[] = "Password reset link is invalid or has expired. Please request a new one.";
        } elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
             // No token provided on GET request
             $errors[] = "No reset token provided. Please use the link from your email.";
        }
         $token_valid = false; // Ensure form is not shown if token is invalid/missing
    }

} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    // No token in GET request URL
    $errors[] = "No reset token provided. Please use the link from your email.";
    $token_valid = false;
}

// --- Include Header ---
require_once 'includes/header.php';
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Reset Your Password</h4>
                </div>
                <div class="card-body">

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php foreach ($errors as $error): ?>
                                <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                            <?php // Show the "request new link" only if the failure wasn't just missing fields on POST ?>
                            <?php if (strpos(implode(' ', $errors), 'invalid or has expired') !== false || strpos(implode(' ', $errors), 'No reset token') !== false): ?>
                                <hr>
                                <a href="forgot_password.php" class="alert-link">Request a new reset link?</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($success_message): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                            <hr>
                            <a href="login.php" class="alert-link">Proceed to Login</a>
                        </div>
                    <?php endif; ?>

                    <?php /* Show form ONLY if token was valid AND there's no success message */ ?>
                    <?php if ($token_valid && !$success_message): ?>
                    <form action="reset_password.php" method="POST" novalidate>
                        <?php /* Use the validated token value */ ?>
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token_from_url_or_form); ?>">

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <?php // Check specific error messages for more targeted feedback if needed ?>
                            <input type="password" class="form-control <?php echo (!empty($errors) && (strpos(implode(' ', $errors), 'Password') !== false || strpos(implode(' ', $errors), 'required') !== false)) ? 'is-invalid' : ''; ?>" id="password" name="password" required aria-describedby="passwordHelp">
                            <div id="passwordHelp" class="form-text">
                                Must be at least 6 characters long.
                            </div>
                            <?php if (!empty($errors) && (strpos(implode(' ', $errors), 'Password must be') !== false || strpos(implode(' ', $errors), 'password fields are required') !== false)): ?>
                                <div class="invalid-feedback">
                                    <?php echo htmlspecialchars(implode(', ', array_filter($errors, fn($e) => strpos($e, 'Password') !== false || strpos($e, 'required') !== false))); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                         <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control <?php echo (!empty($errors) && (strpos(implode(' ', $errors), 'match') !== false || strpos(implode(' ', $errors), 'required') !== false)) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                             <?php if (!empty($errors) && (strpos(implode(' ', $errors), 'match') !== false || strpos(implode(' ', $errors), 'password fields are required') !== false)): ?>
                                <div class="invalid-feedback">
                                     <?php echo htmlspecialchars(implode(', ', array_filter($errors, fn($e) => strpos($e, 'match') !== false || strpos($e, 'required') !== false))); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <button type="submit" name="submit_reset_password" class="btn btn-primary w-100">Update Password</button>
                    </form>
                     <?php endif; ?>

                     <?php /* Fallback message if GET request had no token and no errors were added yet (edge case) */ ?>
                     <?php if (!$token_valid && empty($errors) && empty($success_message) && $_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['token'])):
                        echo '<p class="text-muted">Please use the password reset link sent to your email.</p>';
                        echo '<a href="forgot_password.php">Request a new link?</a>';
                     endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>