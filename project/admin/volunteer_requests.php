<?php
include '../db/db.php'; // adjust path if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Volunteer Pickup Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { overflow-x: hidden; }
        .main-content { margin-left: 240px; padding: 20px; }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h2 class="mb-4">Volunteer Pickup Requests</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Aadhaar</th>
                    <th>Availability</th>
                    <th>Authority</th>
                    <th>Authority ID</th>
                    <th>Skills</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM volunteers");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['aadhaar']) ?></td>
                        <td><?= htmlspecialchars($row['availability']) ?></td>
                        <td><?= htmlspecialchars($row['authority']) ?></td>
                        <td><?= htmlspecialchars($row['authority_id']) ?></td>
                        <td><?= htmlspecialchars($row['skills']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td><?= htmlspecialchars($row['registered_at']) ?></td>
                        <td>
                            <?php if ($row['status'] === 'pending'): ?>
                                <a href="approve_volunteer.php?id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                                <a href="cancel_volunteer_request.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Cancel</a>
                            <?php else: ?>
                                <span class="text-muted">No actions</span>
                            <?php endif; ?>
                            <a href="delete_volunteer_request.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm mt-1">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="12" class="text-center">No requests found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const userType = localStorage.getItem('user_type');
    if (userType !== 'admin') {
        alert("Access denied!");
        window.location.href = '../index.php';
    }
</script>

</body>
</html>
