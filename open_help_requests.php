<?php
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

// Handle donation form submission
$donation_success = $donation_error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['help_request_id'])) {
    $help_request_id = intval($_POST['help_request_id']);
    $donor_name = trim($_POST['donor_name'] ?? '');
    $donor_email = trim($_POST['donor_email'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);

    if ($help_request_id && $donor_name && $donor_email && $amount > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO help_request_donations (help_request_id, donor_name, donor_email, amount, donated_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$help_request_id, $donor_name, $donor_email, $amount]);
            $donation_success = "Thank you for your support! Please complete your payment using the UPI or bank details provided above.";
        } catch (PDOException $e) {
            $donation_error = "Could not record your donation. Please try again.";
        }
    } else {
        $donation_error = "Please fill in all fields and enter a valid amount.";
    }
}

try {
    $stmt = $pdo->query("SELECT * FROM help_requests WHERE is_approved = 1 ORDER BY created_at DESC");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $requests = [];
}
?>

<div class="container py-5">
    <h2 class="fw-bold text-center mb-4">Open Help Requests</h2>
    <p class="text-center mb-5">Browse verified requests for urgent support. Click "Donate to This Request" to help directly.</p>
    <?php if ($donation_success): ?>
        <div class="alert alert-success text-center"><?php echo htmlspecialchars($donation_success); ?></div>
    <?php elseif ($donation_error): ?>
        <div class="alert alert-danger text-center"><?php echo htmlspecialchars($donation_error); ?></div>
    <?php endif; ?>
    <div class="row g-4">
        <?php if (empty($requests)): ?>
            <div class="col-12 text-center text-muted">No help requests available at the moment.</div>
        <?php else:
            foreach ($requests as $req): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($req['title']); ?></h5>
                        <p class="card-text small"><?php echo nl2br(htmlspecialchars($req['description'])); ?></p>
                        <?php if ($req['document_path']): ?>
                            <a href="<?php echo htmlspecialchars($req['document_path']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary mb-2">
                                View Document
                            </a>
                        <?php endif; ?>
                        <div class="mb-2">
                            <span class="fw-semibold">Amount Needed:</span>
                            ₹<?php echo number_format($req['amount_needed'], 2); ?>
                        </div>
                        <div class="mb-2">
                            <span class="fw-semibold">UPI ID:</span>
                            <span class="text-break"><?php echo htmlspecialchars($req['upi_id']); ?></span>
                        </div>
                        <?php if ($req['bank_details']): ?>
                            <div class="mb-2">
                                <span class="fw-semibold">Bank Details:</span>
                                <span class="text-break"><?php echo nl2br(htmlspecialchars($req['bank_details'])); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <!-- Donate Button triggers collapse -->
                        <button class="btn btn-success w-100 mb-2" type="button" data-bs-toggle="collapse" data-bs-target="#donateForm<?php echo $req['id']; ?>" aria-expanded="false" aria-controls="donateForm<?php echo $req['id']; ?>">
                            Donate to This Request
                        </button>
                        <div class="collapse" id="donateForm<?php echo $req['id']; ?>">
                            <form method="post" class="border rounded p-3 bg-light">
                                <input type="hidden" name="help_request_id" value="<?php echo $req['id']; ?>">
                                <div class="mb-2">
                                    <label class="form-label mb-1" for="donor_name_<?php echo $req['id']; ?>">Your Name *</label>
                                    <input type="text" class="form-control form-control-sm" id="donor_name_<?php echo $req['id']; ?>" name="donor_name" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-1" for="donor_email_<?php echo $req['id']; ?>">Your Email *</label>
                                    <input type="email" class="form-control form-control-sm" id="donor_email_<?php echo $req['id']; ?>" name="donor_email" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label mb-1" for="amount_<?php echo $req['id']; ?>">Amount (INR) *</label>
                                    <input type="number" class="form-control form-control-sm" id="amount_<?php echo $req['id']; ?>" name="amount" min="1" step="0.01" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm w-100">Record My Donation</button>
                                <div class="form-text mt-1">After submitting, please complete your payment using the UPI or bank details attached.</div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
