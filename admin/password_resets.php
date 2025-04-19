<?php
require_once 'includes/admin_header.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/send_mail.php'; // Use central mail helper
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$feedback_message = '';
$feedback_type = 'info';

// --- Admin Self Password Reset ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_self_reset'])) {
    $admin_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    if (!$current_password || !$new_password || !$confirm_password) {
        $feedback_message = 'All fields are required for admin password reset.';
        $feedback_type = 'danger';
    } elseif ($new_password !== $confirm_password) {
        $feedback_message = 'New passwords do not match.';
        $feedback_type = 'danger';
    } elseif (strlen($new_password) < 6) {
        $feedback_message = 'New password must be at least 6 characters.';
        $feedback_type = 'danger';
    } else {
        // Fetch admin's current hash
        $stmt = $pdo->prepare('SELECT password, email, first_name FROM users WHERE user_id = :uid AND role = "admin"');
        $stmt->execute([':uid' => $admin_id]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($admin && password_verify($current_password, $admin['password'])) {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt2 = $pdo->prepare('UPDATE users SET password = :pw WHERE user_id = :uid');
            $stmt2->execute([':pw' => $new_hash, ':uid' => $admin_id]);
            // Optional: Send email notification to admin
            $subject = 'Your FoodShare admin password was changed';
            $body = '<p>Hello ' . htmlspecialchars($admin['first_name']) . ',</p>' .
                '<p>Your admin password was changed. If this was not you, please contact support immediately.</p>' .
                '<p>Regards,<br>FoodShare System</p>';
            $altBody = "Hello {$admin['first_name']},\nYour admin password was changed. If this was not you, please contact support immediately.\nRegards, FoodShare System";
            sendMail($admin['email'], $admin['first_name'], $subject, $body, $altBody);
            $feedback_message = 'Your password has been updated.';
            $feedback_type = 'success';
        } else {
            $feedback_message = 'Current password is incorrect.';
            $feedback_type = 'danger';
        }
    }
}

// Fetch all password reset requests
$pending = [];
try {
    $stmt = $pdo->query("SELECT pr.id, pr.requested_at, pr.approved, u.first_name, u.last_name, u.email FROM password_resets pr JOIN users u ON pr.user_id = u.user_id ORDER BY pr.requested_at DESC");
    $pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Fetch password resets error: ' . $e->getMessage());
}
?>
<!-- Page Title & Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><i class="bi bi-key-fill me-2"></i>Password Resets</h1>
    <!-- Optional: Add quick link or info here -->
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-person-fill me-2"></i>Admin Password Reset</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <input type="hidden" name="admin_self_reset" value="1">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i>Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Password Reset Requests Log</h5>
            </div>
            <div class="card-body">
                <?php if ($feedback_message): ?>
                    <div class="alert alert-<?php echo $feedback_type; ?> mb-3">
                        <?php echo htmlspecialchars($feedback_message); ?>
                    </div>
                <?php endif; ?>
                <?php if (empty($pending)): ?>
                    <p class="text-center text-muted mb-0">No password reset requests found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Requested At</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending as $req): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($req['first_name'] . ' ' . $req['last_name']); ?></td>
                                        <td><?php echo htmlspecialchars($req['email']); ?></td>
                                        <td><?php echo htmlspecialchars($req['requested_at']); ?></td>
                                        <td>
                                            <?php echo ($req['approved'] == 1) ? '<span class="badge bg-success">Sent</span>' : '<span class="badge bg-secondary">Pending</span>'; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/admin_footer.php'; ?>
