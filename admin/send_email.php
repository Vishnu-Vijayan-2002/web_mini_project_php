<?php
require_once 'includes/admin_header.php';
require_once __DIR__ . '/../includes/send_mail.php';

// Fetch all volunteers and donors for personal email selection
$users = [];
try {
    $stmt = $pdo->query("SELECT user_id, first_name, last_name, email, role FROM users WHERE role IN ('volunteer', 'donor') ORDER BY role, first_name, last_name");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Fetch users for email error: ' . $e->getMessage());
}

$email_status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $recipient_type = $_POST['recipient_type'] ?? '';
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $personal_user_id = $_POST['personal_user_id'] ?? '';

    $recipients = [];
    if ($recipient_type === 'group') {
        $group = $_POST['group'] ?? '';
        if ($group === 'volunteers') {
            $recipients = array_filter($users, fn($u) => $u['role'] === 'volunteer');
        } elseif ($group === 'donors') {
            $recipients = array_filter($users, fn($u) => $u['role'] === 'donor');
        } elseif ($group === 'all') {
            $recipients = $users;
        }
    } elseif ($recipient_type === 'personal' && $personal_user_id) {
        foreach ($users as $u) {
            if ($u['user_id'] == $personal_user_id) {
                $recipients[] = $u;
                break;
            }
        }
    }

    if ($recipients && $subject && $message) {
        $sent_count = 0;
        foreach ($recipients as $user) {
            $to = $user['email'];
            $toName = $user['first_name'] . ' ' . $user['last_name'];
            $body = nl2br(htmlspecialchars($message));
            $altBody = $message;
            if (sendMail($to, $toName, $subject, $body, $altBody)) {
                $sent_count++;
            }
        }
        $email_status = "Email sent to $sent_count user(s).";
    } else {
        $email_status = "Please fill in all fields and select at least one recipient.";
    }
}
?>
<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-envelope-fill me-2"></i>Send Email to Volunteers/Donors</h5>
                </div>
                <div class="card-body">
                    <?php if ($email_status): ?>
                        <div class="alert alert-info"> <?php echo htmlspecialchars($email_status); ?> </div>
                    <?php endif; ?>
                    <form method="post" action="">
                        <div class="mb-3">
                            <label class="form-label">Send To</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="recipient_type" id="groupRadio" value="group" checked onclick="toggleRecipientType()">
                                <label class="form-check-label" for="groupRadio">Group</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="recipient_type" id="personalRadio" value="personal" onclick="toggleRecipientType()">
                                <label class="form-check-label" for="personalRadio">Personal</label>
                            </div>
                        </div>
                        <div id="groupSelect" class="mb-3">
                            <label for="group" class="form-label">Group</label>
                            <select class="form-select" id="group" name="group">
                                <option value="volunteers">All Volunteers</option>
                                <option value="donors">All Donors</option>
                                <option value="all">All Volunteers & Donors</option>
                            </select>
                        </div>
                        <div id="personalSelect" class="mb-3" style="display:none;">
                            <label for="personal_user_id" class="form-label">Select User</label>
                            <select class="form-select" id="personal_user_id" name="personal_user_id">
                                <option value="">-- Select --</option>
                                <?php foreach ($users as $u): ?>
                                    <option value="<?php echo $u['user_id']; ?>">
                                        <?php echo htmlspecialchars(ucfirst($u['role']) . ': ' . $u['first_name'] . ' ' . $u['last_name'] . ' (' . $u['email'] . ')'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                        </div>
                        <button type="submit" name="send_email" class="btn btn-primary">Send Email</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function toggleRecipientType() {
    var groupRadio = document.getElementById('groupRadio');
    var groupSelect = document.getElementById('groupSelect');
    var personalSelect = document.getElementById('personalSelect');
    if (groupRadio.checked) {
        groupSelect.style.display = '';
        personalSelect.style.display = 'none';
    } else {
        groupSelect.style.display = 'none';
        personalSelect.style.display = '';
    }
}
// Ensure correct display on page load
window.onload = toggleRecipientType;
</script>
<?php require_once 'includes/admin_footer.php'; ?>
