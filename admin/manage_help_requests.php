<?php
// 1. Include necessary setup FIRST (authentication, potentially database connection if not handled by auth)
// admin_auth.php likely starts the session and checks login status.
// Ensure it doesn't output anything itself.
require_once 'includes/admin_auth.php';

// Make sure $pdo is available here. If admin_auth.php doesn't include the DB connection,
// you might need to include your database connection file here as well.
// Example: require_once '../includes/db_connect.php'; // Adjust path if needed

// Helper function (can stay here or be moved to a functions file)
function send_status_email($to, $name, $status, $title) {
    $subject = "Your Help Request Status Update";
    $status_text = $status == 1 ? "approved" : "rejected";
    $message = "Dear $name,\n\nYour help request titled \"$title\" has been $status_text by the admin.\n\nThank you for using FoodShare.";
    // Consider using a more robust mailer library in a real application
    $headers = "From: no-reply@yourdomain.com\r\n"; // Use your actual domain
    $headers .= "Reply-To: no-reply@yourdomain.com\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    @mail($to, $subject, $message, $headers); // Use @ cautiously, better to handle potential errors
}

// 2. Process actions (Approve/Reject) BEFORE any HTML output
if (isset($_GET['action'], $_GET['id']) && in_array($_GET['action'], ['approve', 'reject'])) {
    // Ensure $pdo is available from included files
    if (!isset($pdo)) {
        die('Database connection not available. Check includes.'); // Or handle error appropriately
    }

    $id = intval($_GET['id']);
    $is_approved = $_GET['action'] === 'approve' ? 1 : -1;

    // Fetch email and name for notification BEFORE updating
    $stmtFetch = $pdo->prepare("SELECT email, full_name, title FROM help_requests WHERE id = ?");
    $stmtFetch->execute([$id]);
    $req = $stmtFetch->fetch(PDO::FETCH_ASSOC);

    // Update the status
    $stmtUpdate = $pdo->prepare("UPDATE help_requests SET is_approved = ? WHERE id = ?");
    $success = $stmtUpdate->execute([$is_approved, $id]);

    // Send email notification only if update was successful and email exists
    if ($success && $req && !empty($req['email'])) {
        send_status_email($req['email'], $req['full_name'], $is_approved, $req['title']);
    }

    // Redirect *before* any HTML output
    header("Location: manage_help_requests.php");
    exit; // IMPORTANT: Always exit after a header redirect
}

// 3. NOW include the header file which starts HTML output
require_once 'includes/admin_header.php';

// 4. Fetch data for displaying the page (can happen after header include)
$requests = [];
// Ensure $pdo is available
if (isset($pdo)) {
    try {
        $stmt = $pdo->query("SELECT * FROM help_requests ORDER BY created_at DESC");
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Log error $e->getMessage();
        echo '<div class="alert alert-danger">Error fetching help requests.</div>';
        $requests = []; // Ensure $requests is an array
    }
} else {
     echo '<div class="alert alert-danger">Database connection not available.</div>';
     $requests = []; // Ensure $requests is an array
}
?>

<!-- 5. Start HTML content -->
<div class="container-fluid px-0">
    <h2 class="mb-4">Help Requests</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Age</th>
                    <th>Disease</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Account No.</th>
                    <th>IFSC</th>
                    <th>Branch</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Amount Needed</th>
                    <th>UPI ID</th>
                    <th>Bank Details</th>
                    <th>Document</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($requests)): ?>
                <tr><td colspan="18" class="text-center">No help requests found.</td></tr>
            <?php else:
                $counter = 1; // Use a counter instead of array index $i
                foreach ($requests as $row): ?>
                <tr>
                    <td><?php echo $counter++; ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['age']); ?></td>
                    <td><?php echo htmlspecialchars($row['disease']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['account_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['ifsc_code']); ?></td>
                    <td><?php echo htmlspecialchars($row['branch_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                    <td style="max-width:200px; word-wrap: break-word;"><?php echo nl2br(htmlspecialchars($row['description'])); ?></td>
                    <td>₹<?php echo number_format($row['amount_needed'], 2); ?></td>
                    <td><?php echo htmlspecialchars($row['upi_id']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['bank_details'])); ?></td>
                    <td>
                        <?php if (!empty($row['document_path'])): ?>
                            <?php
                                // Make sure the path starts correctly from the web root perspective
                                $docPath = '../' . ltrim($row['document_path'], '/');
                            ?>
                            <a href="<?php echo htmlspecialchars($docPath); ?>" target="_blank" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-file-earmark-text"></i> View
                            </a>
                        <?php else: ?>
                            <span class="text-muted">None</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        if ($row['is_approved'] == 1) echo '<span class="badge bg-success">Approved</span>';
                        elseif ($row['is_approved'] == -1) echo '<span class="badge bg-danger">Rejected</span>';
                        else echo '<span class="badge bg-warning text-dark">Pending</span>';
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($row['created_at']))); // Optional: Format date ?></td>
                    <td class="action-buttons">
                        <?php if ($row['is_approved'] == 0): // Only show actions if pending ?>
                            <a href="?action=approve&id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm mb-1" title="Approve">
                                <i class="bi bi-check-lg"></i>
                            </a>
                            <a href="?action=reject&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm mb-1" title="Reject">
                                <i class="bi bi-x-lg"></i>
                            </a>
                        <?php else: ?>
                             <span class="text-muted">Processed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
// 6. Include the footer file
require_once 'includes/admin_footer.php'; // Assuming you have a footer file
?>