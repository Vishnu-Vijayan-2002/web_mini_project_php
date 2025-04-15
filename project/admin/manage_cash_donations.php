<?php
include '../db/db.php';

// GROUP BY to avoid duplicate rows
$sql = "SELECT cr.*, MIN(cd.file_path) AS certificate 
        FROM cash_requests AS cr 
        LEFT JOIN cash_documents AS cd ON cr.id = cd.request_id 
        WHERE cr.status IN ('pending', 'approved', 'rejected')
        GROUP BY cr.id";

$cashRequests = $conn->query($sql);

if (!$cashRequests) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Cash Donations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
        }

        .main-content {
            margin-left: 240px;
            padding: 30px;
        }

        h2 {
            color:rgb(8, 78, 208);
            font-weight: bold;
            text-align:center;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
            padding: 10px;
            word-wrap: break-word;
            white-space: normal;
        }

        thead {
            background-color: #0d6efd;
            color: #fff;
        }

        tbody tr:hover {
            background-color: #f1f3f5;
        }

        td:nth-child(4), td:nth-child(5), td:nth-child(6) {
            word-break: break-word;
        }

        th:nth-child(6), td:nth-child(6) {
            width: 220px; /* Bank Details */
            white-space: pre-line;
        }

        th:nth-child(10), td:nth-child(10) {
            width: 120px; /* Actions - reduced */
        }

        th:nth-child(11), td:nth-child(11) {
            width: 140px; /* Approved At */
        }

        .btn-success, .btn-danger, .btn-secondary {
            font-weight: bold;
            padding: 8px 12px;
            border-radius: 5px;
        }

        td .btn {
            margin: 2px;
        }

        .certificate-link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: bold;
        }

        .certificate-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h2 class="mb-4">Manage Cash Donations</h2>
    <div class="card">
        <h5 class="text-muted text-center">Cash Donation Requests</h5>
        <div class="table-container">
            <?php if ($cashRequests->num_rows > 0): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Disease</th>
                            <th>Contact</th>
                            <th>UPI ID</th>
                            <th>Bank Details</th>
                            <th>Amount Needed</th>
                            <th>Medical Certificate</th>
                            <th>Status</th>
                            <th>Actions</th>
                            <th>Action Taken</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $cashRequests->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['full_name']) ?></td>
                                <td><?= htmlspecialchars($row['age']) ?></td>
                                <td><?= htmlspecialchars($row['disease']) ?></td>
                                <td><?= htmlspecialchars($row['contact_info']) ?></td>
                                <td><?= htmlspecialchars($row['upi_id']) ?></td>
                                <td><?= nl2br(htmlspecialchars($row['bank_details'])) ?></td>
                                <td>₹<?= htmlspecialchars($row['required_amount']) ?></td>
                                <td>
                                    <?php if (!empty($row['certificate'])): ?>
                                        <a href="../<?= htmlspecialchars($row['certificate']) ?>" class="certificate-link" target="_blank">View</a>
                                    <?php else: ?>
                                        <span class="text-muted">No File</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'approved'): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php elseif ($row['status'] === 'rejected'): ?>
                                        <span class="badge bg-danger">Rejected</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center">
                                        <?php if ($row['status'] === 'pending'): ?>
                                            <form action="cash_request_action.php" method="POST" class="me-1">
                                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                                <button type="submit" name="approve" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="cash_request_action.php" method="POST" class="me-1">
                                                <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                                <button type="submit" name="reject" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        <?php endif; ?>
                                        <form action="cash_request_delete.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                                            <button type="submit" name="delete" class="btn btn-secondary btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'approved' || $row['status'] === 'rejected'): ?>
                                        <?= htmlspecialchars($row['approved_at']) ?>
                                    <?php else: ?>
                                        <span class='text-muted'>Pending</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center text-muted">No pending cash donation requests found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
