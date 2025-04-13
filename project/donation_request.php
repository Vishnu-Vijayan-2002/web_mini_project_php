<?php
$conn = new mysqli("localhost", "root", "", "php_mini_project");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$submitted = false;
$uploadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $age = $_POST['age'];
    $disease = $_POST['disease'];
    $contact_info = $_POST['contact'];
    $upi_id = $_POST['upi'];
    $account_number = $_POST['account_number'];
    $ifsc = $_POST['ifsc'];
    $branch = $_POST['branch'];
    $required_amount = $_POST['amount'];

    $bank_details = "Account No: $account_number, IFSC: $ifsc, Branch: $branch";

    // Upload File
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $certificateName = basename($_FILES["certificate"]["name"]);
    $fileExtension = strtolower(pathinfo($certificateName, PATHINFO_EXTENSION));
    $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
    $newFileName = time() . "_" . preg_replace("/[^a-zA-Z0-9.\-_]/", "", $certificateName);
    $targetFilePath = $targetDir . $newFileName;

    if (in_array($fileExtension, $allowedTypes)) {
        if (move_uploaded_file($_FILES["certificate"]["tmp_name"], $targetFilePath)) {
            $uploadedFile = $targetFilePath;

            // Step 1: Insert into cash_requests
            $stmt = $conn->prepare("INSERT INTO cash_requests 
                (full_name, age, disease, contact_info, upi_id, bank_details, required_amount) 
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissssd", 
                $full_name, $age, $disease, $contact_info, $upi_id, $bank_details, $required_amount);

            if ($stmt->execute()) {
                $request_id = $stmt->insert_id; // Get auto-incremented ID

                // Step 2: Insert into documents table
                $doc_stmt = $conn->prepare("INSERT INTO cash_documents (request_id, file_path, document_type) VALUES (?, ?, ?)");
                $doc_type = "medical_certificate";
                $doc_stmt->bind_param("iss", $request_id, $uploadedFile, $doc_type);
                $doc_stmt->execute();
                $doc_stmt->close();

                $submitted = true;
            } else {
                $uploadError = "❌ Database insert failed: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $uploadError = "❌ File upload failed.";
        }
    } else {
        $uploadError = "❌ Invalid file type. Only PDF, JPG, JPEG, PNG are allowed.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Medical Donation Request Form</title>
  <link rel="stylesheet" href="donation_request.css">
</head>
<body>

<?php if ($submitted): ?>
  <div class="result">
    <h2 class="success">✅ Request Submitted Successfully</h2>
    <p><strong>Name:</strong> <?= htmlspecialchars($full_name) ?></p>
    <p><strong>Age:</strong> <?= htmlspecialchars($age) ?></p>
    <p><strong>Disease:</strong> <?= htmlspecialchars($disease) ?></p>
    <p><strong>Contact Info:</strong> <?= htmlspecialchars($contact_info) ?></p>
    <p><strong>UPI ID:</strong> <?= htmlspecialchars($upi_id) ?></p>
    <p><strong>Bank Details:</strong> <?= htmlspecialchars($bank_details) ?></p>
    <p><strong>Required Amount:</strong> ₹<?= htmlspecialchars($required_amount) ?></p>
    <p><strong>Medical Certificate:</strong> <a href="<?= $uploadedFile ?>" target="_blank">View File</a></p>
  </div>
<?php else: ?>
  <h2 style="text-align:center;">Medical Donation Request Form</h2>
  <form action="" method="POST" enctype="multipart/form-data">
    <label>Full Name</label>
    <input type="text" name="full_name" required>

    <label>Age</label>
    <input type="number" name="age" required>

    <label>Disease</label>
    <input type="text" name="disease" required>

    <label>Contact Info (Phone or Email)</label>
    <input type="text" name="contact" required>

    <label>UPI ID</label>
    <input type="text" name="upi" required>

    <label>Bank Account Number</label>
    <input type="text" name="account_number" required>

    <label>IFSC Code</label>
    <input type="text" name="ifsc" required>

    <label>Branch Name</label>
    <input type="text" name="branch" required>

    <label>Required Amount</label>
    <input type="number" name="amount" required step="0.01">

    <label>Medical Certificate (Upload)</label>
    <input type="file" name="certificate" accept=".pdf,.jpg,.jpeg,.png" required>

    <?php if (!empty($uploadError)): ?>
      <p class="error"><?= $uploadError ?></p>
    <?php endif; ?>

    <button type="submit">Submit Request</button>
  </form>
<?php endif; ?>

</body>
</html>
