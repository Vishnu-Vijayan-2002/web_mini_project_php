<?php
require_once 'includes/admin_header.php'; // Includes Bootstrap, navbar, sidebar
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$feedback_message = '';
$feedback_type = 'danger'; // Default to danger for errors

// --- Handle Admin Password Reset ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_reset_password'])) {
    $reset_user_id = intval($_POST['reset_user_id'] ?? 0);
    $new_password = trim($_POST['new_password'] ?? '');
    if ($reset_user_id && $new_password && strlen($new_password) >= 6) {
        try {
            $stmt = $pdo->prepare("SELECT email, first_name, last_name FROM users WHERE user_id = :uid AND role IN ('donor','volunteer')");
            $stmt->execute([':uid' => $reset_user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt2 = $pdo->prepare("UPDATE users SET password = :pw WHERE user_id = :uid");
                $stmt2->execute([':pw' => $new_hash, ':uid' => $reset_user_id]);
                // Send email notification
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.example.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'your_email@example.com';
                    $mail->Password = 'your_email_password';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;
                    $mail->setFrom('no-reply@foodshare.com', 'FoodShare Admin');
                    $mail->addAddress($user['email'], $user['first_name'] . ' ' . $user['last_name']);
                    $mail->isHTML(true);
                    $mail->Subject = 'Your FoodShare password has been reset by admin';
                    $mail->Body = '<p>Hello ' . htmlspecialchars($user['first_name']) . ',</p>' .
                        '<p>Your password has been reset by an administrator. Your new password is:</p>' .
                        '<p><b>' . htmlspecialchars($new_password) . '</b></p>' .
                        '<p>Please log in and change your password as soon as possible.</p>' .
                        '<p>Regards,<br>FoodShare Team</p>';
                    $mail->AltBody = "Hello {$user['first_name']},\nYour password has been reset by an administrator. Your new password is: {$new_password}\nPlease log in and change your password as soon as possible.\nRegards, FoodShare Team";
                    $mail->send();
                    $feedback_message = 'Password reset and email sent to user.';
                    $feedback_type = 'success';
                } catch (Exception $e) {
                    error_log('PHPMailer Error: ' . $mail->ErrorInfo);
                    $feedback_message = 'Password reset, but failed to send email.';
                    $feedback_type = 'warning';
                }
            } else {
                $feedback_message = 'User not found.';
            }
        } catch (PDOException $e) {
            error_log('Admin password reset error: ' . $e->getMessage());
            $feedback_message = 'Database error during password reset.';
        }
    } else {
        $feedback_message = 'Please enter a valid password (min 6 chars).';
    }
}

// --- Fetch Donors ---
$donors = [];
try {
    $stmt_donors = $pdo->query("SELECT user_id, first_name, last_name, email, phone, registration_date FROM users WHERE role = 'donor' ORDER BY registration_date DESC");
    $donors = $stmt_donors->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { error_log("Fetch donors error: " . $e->getMessage()); $feedback_message = "Could not load donor data."; }

// --- Fetch Approved Volunteers ---
$approved_volunteers = [];
try {
    $stmt_volunteers = $pdo->query("SELECT user_id, first_name, last_name, email, phone, registration_date FROM users WHERE role = 'volunteer' AND is_approved = 1 ORDER BY registration_date DESC");
    $approved_volunteers = $stmt_volunteers->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { error_log("Fetch approved volunteers error: " . $e->getMessage()); $feedback_message = ($feedback_message ? $feedback_message." " : "")."Could not load volunteer data."; }

?>

<!-- Page Title & Header -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Users</h1>
     <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-outline-success" disabled><i class="bi bi-person-plus-fill me-1"></i> Add User</button>
    </div>
</div>

<!-- Display Feedback Message -->
<?php if ($feedback_message): ?>
    <div class="alert alert-<?php echo $feedback_type; ?> alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($feedback_message); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>


<!-- Donors Card -->
<div class="card shadow-sm mb-4" id="donors">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-gift-fill me-2 text-success"></i>Registered Donors</h5>
    </div>
    <div class="card-body">
        <?php if (empty($donors) && !$feedback_message): ?>
            <p class="text-center text-muted">No donors found.</p>
        <?php elseif (!empty($donors)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Registered On</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donors as $user): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone'] ?: '-'); ?></td>
                                <td><small><?php echo date("Y-m-d", strtotime($user['registration_date'])); ?></small></td>
                                <td class="text-center action-buttons">
                                   <a href="#" class="text-secondary p-0 me-1" title="View Profile (Not implemented)"><i class="bi bi-eye-fill fs-5"></i></a>
                                   <a href="#" class="text-info p-0 me-1" title="View Donations (Not implemented)"><i class="bi bi-list-task fs-5"></i></a>
                                   <button class="btn btn-link text-warning p-0" disabled title="Deactivate (Not implemented)"><i class="bi bi-person-dash-fill fs-5"></i></button>
                                   <button class="btn btn-link text-danger p-0" data-bs-toggle="modal" data-bs-target="#resetPasswordModal" data-user-id="<?php echo $user['user_id']; ?>" data-user-name="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" title="Reset Password"><i class="bi bi-key-fill fs-5"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Table Footer -->
             <div class="d-flex justify-content-between align-items-center mt-2">
                 <small class="text-muted">Showing <?php echo count($donors); ?> <?php echo (count($donors) === 1) ? 'donor' : 'donors'; ?></small>
             </div>
        <?php endif; ?>
    </div>
</div>


<!-- Approved Volunteers Card -->
<div class="card shadow-sm" id="approved-volunteers">
     <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-person-check-fill me-2 text-success"></i>Approved Volunteers</h5>
    </div>
     <div class="card-body">
         <?php if (empty($approved_volunteers) && !$feedback_message): ?>
            <p class="text-center text-muted">No approved volunteers found.</p>
        <?php elseif (!empty($approved_volunteers)): ?>
             <div class="table-responsive">
                <table class="table table-striped table-hover table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Registered On</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                     <tbody>
                        <?php foreach ($approved_volunteers as $user): ?>
                            <tr>
                                <td><?php echo $user['user_id']; ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone'] ?: '-'); ?></td>
                                <td><small><?php echo date("Y-m-d", strtotime($user['registration_date'])); ?></small></td>
                                <td class="text-center action-buttons">
                                   <a href="#" class="text-secondary p-0 me-1" title="View Profile (Not implemented)"><i class="bi bi-eye-fill fs-5"></i></a>
                                   <a href="#" class="text-info p-0 me-1" title="View Tasks (Not implemented)"><i class="bi bi-card-checklist fs-5"></i></a>
                                   <button class="btn btn-link text-warning p-0" disabled title="Deactivate (Not implemented)"><i class="bi bi-person-dash-fill fs-5"></i></button>
                                   <button class="btn btn-link text-danger p-0" data-bs-toggle="modal" data-bs-target="#resetPasswordModal" data-user-id="<?php echo $user['user_id']; ?>" data-user-name="<?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>" title="Reset Password"><i class="bi bi-key-fill fs-5"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
             <!-- Table Footer -->
             <div class="d-flex justify-content-between align-items-center mt-2">
                 <small class="text-muted">Showing <?php echo count($approved_volunteers); ?> approved <?php echo (count($approved_volunteers) === 1) ? 'volunteer' : 'volunteers'; ?></small>
             </div>
        <?php endif; ?>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="reset_user_id" id="resetUserId">
                    <p>Reset password for <span id="resetUserName"></span>.</p>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="new_password" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" name="admin_reset_password">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const resetPasswordModal = document.getElementById('resetPasswordModal');
    resetPasswordModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const userName = button.getAttribute('data-user-name');
        const modalUserIdInput = resetPasswordModal.querySelector('#resetUserId');
        const modalUserNameSpan = resetPasswordModal.querySelector('#resetUserName');
        modalUserIdInput.value = userId;
        modalUserNameSpan.textContent = userName;
    });
</script>

<?php require_once 'includes/admin_footer.php'; // Includes closing tags and Bootstrap JS ?>