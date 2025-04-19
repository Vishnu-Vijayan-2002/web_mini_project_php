<?php
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

$name = $email = $amount = $message = "";
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $message = trim($_POST['message'] ?? '');

    if ($name && $email && $amount > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO cash_donations (name, email, amount, message, donated_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $amount, $message]);
            $success = "Thank you for your generous support!";
            $name = $email = $amount = $message = "";
        } catch (PDOException $e) {
            $error = "Could not process your donation. Please try again.";
        }
    } else {
        $error = "Please fill in all required fields and enter a valid amount.";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <h2 class="mb-4 fw-bold text-center">Support Our Cause</h2>
            <p class="text-center mb-4">Your cash donation helps us reach more people and cover operational costs. Thank you for making a difference!</p>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" class="card p-4 shadow-sm">
                <div class="text-center mb-3">
                    <h5 class="fw-semibold text-primary mb-3">Scan & Pay Instantly</h5>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=foodshare@upi&pn=FoodShare%20Donation&am=100" alt="Scan to Pay QR" class="img-fluid mb-2" style="max-width:180px; display:block; margin:0 auto;">
                    <p class="mb-1"><strong>UPI ID:</strong> <span class="text-monospace">foodshare@upi</span></p>
                    <p class="mb-1"><strong>Account Name:</strong> FoodShare Foundation</p>
                    <p class="mb-2"><small>Scan this QR code with any UPI app (Paytm, Google Pay, PhonePe, etc.) to donate instantly.</small></p>
                    <div class="alert alert-info py-2 px-3 mb-0" style="font-size:0.95em;">
                        After payment, please fill the details below to receive your donation receipt.
                    </div>
                </div>
                <hr class="my-4">
                <h5 class="mb-3 fw-semibold text-secondary text-center">Donation Details</h5>
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($name); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount (INR) *</label>
                    <input type="number" class="form-control" id="amount" name="amount" min="1" step="0.01" required value="<?php echo htmlspecialchars($amount); ?>">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message (optional)</label>
                    <textarea class="form-control" id="message" name="message" rows="2"><?php echo htmlspecialchars($message); ?></textarea>
                </div>
                <button type="submit" class="btn btn-warning w-100 fw-semibold">Donate Now</button>
                <div class="form-text mt-2 text-center">
                    <span class="text-muted">We will email you a receipt after verifying your payment.</span>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
