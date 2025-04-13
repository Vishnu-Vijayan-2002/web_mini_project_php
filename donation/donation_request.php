<?php
// Database connection (XAMPP - MySQL)
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
    $contact = $_POST['contact'];
    $upi = $_POST['upi'];
    $account_number = $_POST['account_number'];
    $ifsc = $_POST['ifsc'];
    $branch = $_POST['branch'];
    $amount = $_POST['amount'];
    $notes = $_POST['notes'];

    // File Upload
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

            // Insert into database
            $stmt = $conn->prepare("INSERT INTO donation_requests 
                (full_name, age, disease, contact, upi, account_number, ifsc, branch, amount, notes, certificate_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissssssiss", 
                $full_name, $age, $disease, $contact, $upi,
                $account_number, $ifsc, $branch, $amount,
                $notes, $uploadedFile
            );

            if ($stmt->execute()) {
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
    <p><strong>Contact:</strong> <?= htmlspecialchars($contact) ?></p>
    <p><strong>UPI ID:</strong> <?= htmlspecialchars($upi) ?></p>
    <p><strong>Account Number:</strong> <?= htmlspecialchars($account_number) ?></p>
    <p><strong>IFSC Code:</strong> <?= htmlspecialchars($ifsc) ?></p>
    <p><strong>Branch Name:</strong> <?= htmlspecialchars($branch) ?></p>
    <p><strong>Required Amount:</strong> ₹<?= htmlspecialchars($amount) ?></p>
    <p><strong>Notes:</strong> <?= nl2br(htmlspecialchars($notes)) ?></p>
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
    <input type="number" name="amount" required>

    <label>Medical Certificate (Upload)</label>
    <input type="file" name="certificate" accept=".pdf,.jpg,.jpeg,.png" required>

    <label>Additional Notes</label>
    <textarea name="notes" rows="4"></textarea>

    <?php if (!empty($uploadError)): ?>
      <p class="error"><?= $uploadError ?></p>
    <?php endif; ?>

    <button type="submit">Submit Request</button>
  </form>
<?php endif; ?>

</body>
</html>
