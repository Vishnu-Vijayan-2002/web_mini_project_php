<?php
require_once 'includes/header.php';
require_once 'includes/db_connect.php';

$title = $description = $amount_needed = $upi_id = $bank_details = "";
$full_name = $age = $disease = $phone = $email = $account_number = $ifsc_code = $branch_name = "";
$success = $error = "";
$document_path = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $amount_needed = floatval($_POST['amount_needed'] ?? 0);
    $upi_id = trim($_POST['upi_id'] ?? '');
    $bank_details = trim($_POST['bank_details'] ?? '');

    $full_name = trim($_POST['full_name'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $disease = trim($_POST['disease'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $account_number = trim($_POST['account_number'] ?? '');
    $ifsc_code = trim($_POST['ifsc_code'] ?? '');
    $branch_name = trim($_POST['branch_name'] ?? '');

    // Handle file upload
    if (!empty($_FILES['document']['name'])) {
        $target_dir = "uploads/help_docs/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $file_name = uniqid("doc_") . "_" . basename($_FILES["document"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['pdf', 'jpg', 'jpeg', 'png'];
        if (in_array($file_type, $allowed_types) && $_FILES["document"]["size"] <= 5*1024*1024) {
            if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
                $document_path = $target_file;
            } else {
                $error = "Failed to upload document.";
            }
        } else {
            $error = "Invalid document type or size (max 5MB, PDF/JPG/PNG).";
        }
    }

    if (
        !$error && $title && $description && $amount_needed > 0 && $upi_id &&
        $full_name && $age > 0 && $disease && $phone && $email
    ) {
        try {
            $stmt = $pdo->prepare("INSERT INTO help_requests 
                (title, description, amount_needed, upi_id, bank_details, document_path, is_approved, created_at,
                 full_name, age, disease, phone, email, account_number, ifsc_code, branch_name)
                VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $title, $description, $amount_needed, $upi_id, $bank_details, $document_path,
                $full_name, $age, $disease, $phone, $email, $account_number, $ifsc_code, $branch_name
            ]);
            $success = "Your request has been submitted for review.";
            $title = $description = $amount_needed = $upi_id = $bank_details = $document_path = "";
            $full_name = $age = $disease = $phone = $email = $account_number = $ifsc_code = $branch_name = "";
        } catch (PDOException $e) {
            $error = "Could not submit your request. Please try again.";
        }
    } elseif (!$error) {
        $error = "Please fill in all required fields (marked with *).";
    }
}
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <h2 class="mb-4 fw-bold text-center">Request for Help</h2>
            <p class="text-center mb-4">Fill out the form below to request support. Attach relevant documents to support your claim. Your request will be reviewed by our team before being listed.</p>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm">
                <div class="mb-3">
                    <label for="full_name" class="form-label">Full Name *</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required value="<?php echo htmlspecialchars($full_name); ?>">
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age *</label>
                    <input type="number" class="form-control" id="age" name="age" min="1" required value="<?php echo htmlspecialchars($age); ?>">
                </div>
                <div class="mb-3">
                    <label for="disease" class="form-label">Disease *</label>
                    <input type="text" class="form-control" id="disease" name="disease" required value="<?php echo htmlspecialchars($disease); ?>">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone *</label>
                    <input type="text" class="form-control" id="phone" name="phone" required value="<?php echo htmlspecialchars($phone); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>
                <div class="mb-3">
                    <label for="account_number" class="form-label">Bank Account Number</label>
                    <input type="text" class="form-control" id="account_number" name="account_number" value="<?php echo htmlspecialchars($account_number); ?>">
                </div>
                <div class="mb-3">
                    <label for="ifsc_code" class="form-label">IFSC Code</label>
                    <input type="text" class="form-control" id="ifsc_code" name="ifsc_code" value="<?php echo htmlspecialchars($ifsc_code); ?>">
                </div>
                <div class="mb-3">
                    <label for="branch_name" class="form-label">Branch Name</label>
                    <input type="text" class="form-control" id="branch_name" name="branch_name" value="<?php echo htmlspecialchars($branch_name); ?>">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label">Title of Request *</label>
                    <input type="text" class="form-control" id="title" name="title" required value="<?php echo htmlspecialchars($title); ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($description); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="amount_needed" class="form-label">Amount Needed (INR) *</label>
                    <input type="number" class="form-control" id="amount_needed" name="amount_needed" min="1" step="0.01" required value="<?php echo htmlspecialchars($amount_needed); ?>">
                </div>
                <div class="mb-3">
                    <label for="upi_id" class="form-label">UPI ID *</label>
                    <input type="text" class="form-control" id="upi_id" name="upi_id" required value="<?php echo htmlspecialchars($upi_id); ?>">
                </div>
                <div class="mb-3">
                    <label for="bank_details" class="form-label">Bank Details (optional)</label>
                    <textarea class="form-control" id="bank_details" name="bank_details" rows="2"><?php echo htmlspecialchars($bank_details); ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="document" class="form-label">Supporting Document (PDF/JPG/PNG, max 5MB)</label>
                    <input type="file" class="form-control" id="document" name="document" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <button type="submit" class="btn btn-danger w-100 fw-semibold">Submit Request</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
