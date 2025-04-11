<?php
include '../db/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Donations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { overflow-x: hidden; }
        .main-content { margin-left: 240px; padding: 20px; }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h2 class="mb-4">Manage Donations</h2>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-info"><?= htmlspecialchars($_GET['msg']) ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Phone</th>
                    <th>Food Type</th>
                    <th>Quantity</th>
                    <th>Address</th>
                    <th>Donation Status</th>
                    <th>Volunteer Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT * FROM donations ORDER BY created_at DESC");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['user_id']) ?></td>
                        <td><?= htmlspecialchars($row['first_name']) ?></td>
                        <td><?= htmlspecialchars($row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['meal_type']) ?></td>
                        <td><?= htmlspecialchars($row['food_quantity']) ?></td>
                        <td><?= htmlspecialchars($row['address']) ?></td>
                        <td><?= htmlspecialchars($row['donation_status']) ?></td>
                        <td><?= htmlspecialchars($row['volunteer_status']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>

                        <td>
                            <button class="btn btn-danger btn-sm" onclick="deleteDonation(<?= $row['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="9" class="text-center">No donations found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    // Access control using localStorage
    const userType = localStorage.getItem('user_type');
    if (userType !== 'admin') {
        alert("Access denied. Admins only.");
        window.location.href = '../index.php';
    }

    function deleteDonation(donationId) {
        if (confirm("Are you sure you want to delete this donation?")) {
            window.location.href = `delete_donation.php?id=${donationId}`;
        }
    }
</script>

</body>
</html>
