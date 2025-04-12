<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../db/db.php';

// Handle email notification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_mail'])) {
    $volunteerId = intval($_POST['volunteer_id']);

    $stmt = $conn->prepare("SELECT name, email FROM volunteers WHERE id = ?");
    if (!$stmt) {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: volunteer_notify.php");
        exit;
    }

    $stmt->bind_param("i", $volunteerId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $volunteer = $result->fetch_assoc();
        $name = $volunteer['name'];
        $email = $volunteer['email'];
        $defaultPassword = $name . "123";
        $subject = "You're Selected as Volunteer!";
        $message = "
            <html>
            <head><title>Volunteer Selection</title></head>
            <body>
                <p>Hi <strong>" . htmlspecialchars($name) . "</strong>,</p>
                <p>🎉 Congratulations! You are selected as a volunteer.</p>
                <p><b>Your login credentials:</b></p>
                <ul>
                    <li><b>Email:</b> " . htmlspecialchars($email) . "</li>
                    <li><b>Password:</b> " . htmlspecialchars($defaultPassword) . "</li>
                </ul>
                <p>Please change your password after login for security.</p>
                <br><p>Regards,<br>Admin Team</p>
            </body>
            </html>
        ";

        $_SESSION['mail_data'] = [
            'email' => $email,
            'fullname' => $name,
            'subject' => $subject,
            'message' => $message,
            'goback' => 'Location: ../volunteer__notification_requests.php'
        ];

        // Instead of immediately redirecting, set a flag to show animation
        $_SESSION['show_mail_animation'] = true;
        $_SESSION['volunteer_name'] = $name;
        // We'll use JavaScript to redirect after the animation
    } else {
        $_SESSION['error'] = "Volunteer not found";
        header("Location: volunteer_notify.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteer Notification Requests</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Animate.css (optional but cool) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        body { overflow-x: hidden; background-color: #f8f9fa; }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            animation: fadeIn 0.6s ease-in-out;
        }
        @media (max-width: 992px) {
            .main-content { margin-left: 0; }
        }
        .table-responsive {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .btn-primary {
            transition: transform 0.2s ease, background-color 0.3s ease;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            background-color: #0056b3;
        }
        .table-hover tbody tr:hover {
            background-color: #f0f9ff;
            transition: background-color 0.3s ease;
        }
        .alert {
            animation: slideFade 0.5s ease-out;
            margin-top: 20px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideFade {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        
        /* Mail sending animation overlay */
        .mail-sending-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }
        
        .mail-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            animation: bounce 1s infinite alternate;
        }
        
        @keyframes bounce {
            from { transform: translateY(0px); }
            to { transform: translateY(-20px); }
        }
        
        .sending-text {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .progress-bar {
            width: 250px;
            height: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .progress {
            height: 100%;
            background-color: #0d6efd;
            border-radius: 5px;
            animation: progress 2s ease-in-out forwards;
        }
        
        @keyframes progress {
            from { width: 0%; }
            to { width: 100%; }
        }
        
        .recipient-name {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<?php if (isset($_SESSION['show_mail_animation']) && $_SESSION['show_mail_animation']): ?>
<div class="mail-sending-overlay" id="mailSendingOverlay">
    <div class="mail-icon">
        <i class="bi bi-envelope-paper"></i>
    </div>
    <div class="sending-text">Sending notification email...</div>
    <div class="progress-bar">
        <div class="progress"></div>
    </div>
    <div class="recipient-name">To: <?= htmlspecialchars($_SESSION['volunteer_name']) ?></div>
</div>
<?php endif; ?>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Notify Volunteers</h2>
            <a href="manage_volunteers.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Volunteers
            </a>
        </div>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__fadeInDown">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__fadeInDown">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Approved Volunteers</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->prepare("SELECT id, name, email FROM volunteers WHERE status = 'approved'");
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0):
                                while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['id']) ?></td>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['email']) ?></td>
                                        <td>
                                            <form method="POST" class="d-inline">
                                                <input type="hidden" name="volunteer_id" value="<?= $row['id'] ?>">
                                                <button type="submit" name="send_mail" class="btn btn-sm btn-primary animate__animated animate__pulse">
                                                    <i class="bi bi-envelope"></i> Send Notification
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-people" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">No approved volunteers found</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const userType = localStorage.getItem('user_type');
    if (userType !== 'admin') {
        alert("Access denied! Redirecting to login...");
        window.location.href = '../index.php';
    }

    document.querySelectorAll('form[method="POST"]').forEach(form => {
        form.addEventListener('submit', function (e) {
            if (!confirm('Are you sure you want to send notification to this volunteer?')) {
                e.preventDefault();
            }
        });
    });
    
    // Handle mail sending animation and redirection
    <?php if (isset($_SESSION['show_mail_animation']) && $_SESSION['show_mail_animation']): ?>
        // After animation completes (progress bar animation is 2s), redirect
        setTimeout(function() {
            window.location.href = "../admin/phpmailer/index.php";
        }, 2500); // 2.5 seconds to allow animation to complete
        
        <?php 
        // Clear the session variables after setting up the animation
        unset($_SESSION['show_mail_animation']);
        unset($_SESSION['volunteer_name']);
        ?>
    <?php endif; ?>
});
</script>

</body>
</html>

<?php if (isset($conn)) $conn->close(); ?>