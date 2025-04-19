<?php
require_once 'includes/db_connect.php'; // Ensure session started and DB connected
$error_message = '';
$submitted_email = ''; // Store submitted email for repopulation

// --- Redirect if already logged in ---
if (isset($_SESSION["user_id"])) {
    // Determine redirect based on role
    $redirect_page = 'index.php'; // Default redirect
    if (isset($_SESSION["role"])) {
        switch ($_SESSION["role"]) {
            case 'admin':
                $redirect_page = 'admin/dashboard.php'; break;
            case 'volunteer':
                $redirect_page = 'volunteer_dashboard.php'; break;
            case 'donor':
                $redirect_page = 'donor_dashboard.php'; break;
        }
    }
    header("Location: " . $redirect_page);
    exit;
}

// --- Handle Login Form Submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $submitted_email = $email; // Store for repopulation

    if (empty($email) || empty($password)) {
        $error_message = "Email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         $error_message = "Please enter a valid email address.";
    } else {
        try {
            $sql = "SELECT user_id, first_name, password, role, is_approved FROM users WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() == 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $user['password'])) { // Verify hashed password
                    if ($user['role'] === 'volunteer' && $user['is_approved'] != 1) {
                         $error_message = "Your volunteer account is pending approval.";
                    } else {
                        // Login successful, store session data
                        $_SESSION["user_id"] = $user['user_id'];
                        $_SESSION["first_name"] = $user['first_name'];
                        $_SESSION["role"] = $user['role'];
                        session_regenerate_id(true); // Regenerate session ID for security

                        // Redirect based on role
                        $redirect_page = 'index.php'; // Default
                         switch ($user["role"]) {
                            case 'admin': $redirect_page = 'admin/dashboard.php'; break;
                            case 'volunteer': $redirect_page = 'volunteer_dashboard.php'; break;
                            case 'donor': $redirect_page = 'donor_dashboard.php'; break;
                        }
                        header("Location: " . $redirect_page);
                        exit;
                    }
                } else {
                    $error_message = "Invalid email or password."; // Incorrect password
                }
            } else {
                $error_message = "Invalid email or password."; // Email not found
            }
        } catch (PDOException $e) {
            error_log("Login failed: " . $e->getMessage());
            $error_message = "An error occurred during login. Please try again later.";
        }
    }
}

// Check for status messages from redirects (e.g., volunteer approval pending)
if (isset($_GET['status']) && $_GET['status'] === 'pending') {
    $error_message = "Your volunteer account is pending approval. Please wait for confirmation.";
}

require_once 'includes/header.php'; // Include Bootstrap header
?>

    <div class="container my-5"> <!-- my-5 adds vertical margin -->
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5 col-xl-4">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    <h4 class="mb-4 text-center fw-semibold" style="color: #ff8c00;">Login to Your Account</h4>
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    <form action="login.php" method="POST" novalidate>
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" class="form-control form-control-lg <?php if (!empty($error_message) && strpos($error_message, 'Email') !== false) echo 'is-invalid'; ?>" id="email" name="email" value="<?php echo htmlspecialchars($submitted_email); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Password</label>
                            <input type="password" class="form-control form-control-lg <?php if (!empty($error_message) && (strpos($error_message, 'password') !== false || strpos($error_message, 'Password') !== false)) echo 'is-invalid'; ?>" id="password" name="password" required>
                        </div>
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-lg text-white fw-bold" style="background: linear-gradient(90deg, #ffb347 0%, #ff8c00 100%); border: none;">Login</button>
                        </div>
                        <div class="text-center small mb-2">
                            <a href="forgot_password.php" class="link-secondary">Forgot Password?</a>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center bg-light py-3 border-0 rounded-bottom">
                    <p class="mb-0">Don't have an account? <a href="signup.php" class="fw-semibold" style="color: #ff8c00;">Sign Up Here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; // Include Bootstrap footer ?>