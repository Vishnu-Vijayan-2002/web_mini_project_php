<?php
include 'header.php';
include 'db/db.php';

$sql = "SELECT cr.*, MIN(cd.file_path) AS certificate
        FROM cash_requests AS cr
        LEFT JOIN cash_documents AS cd ON cr.id = cd.request_id
        WHERE cr.status = 'approved'
        GROUP BY cr.id";

$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Support an Approved Cash Donation Request</h2>

    <div class="row">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-6 mb-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['full_name']) ?> (Age: <?= $row['age'] ?>)</h5>
                        <p class="card-text"><strong>Disease:</strong> <?= htmlspecialchars($row['disease']) ?></p>
                        <p class="card-text"><strong>Required Amount:</strong> ₹<?= htmlspecialchars($row['required_amount']) ?></p>
                        <?php if (!empty($row['certificate'])): ?>
                            <p><a href="<?= htmlspecialchars($row['certificate']) ?>" target="_blank">View Medical Certificate</a></p>
                        <?php endif; ?>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#donateModal<?= $row['id'] ?>">Donate Now</button>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="donateModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="donateModalLabel<?= $row['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="donateModalLabel<?= $row['id'] ?>">Donate to <?= htmlspecialchars($row['full_name']) ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Name:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
                            <p><strong>Disease:</strong> <?= htmlspecialchars($row['disease']) ?></p>
                            <p><strong>Required Amount:</strong> ₹<?= htmlspecialchars($row['required_amount']) ?></p>
                            <p><strong>UPI ID:</strong> <?= htmlspecialchars($row['upi_id']) ?></p>
                            <p><strong>Bank Details:</strong><br><?= nl2br(htmlspecialchars($row['bank_details'])) ?></p>
                            </div>
                        <!-- <div class="modal-footer">
                            <a href="#" class="btn btn-success">Pay Now</a>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div> -->
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
