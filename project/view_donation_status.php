<?php
include './db/db.php';

$userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;

if ($userId <= 0) {
    echo "Invalid user.";
    exit;
}

$sql = "SELECT * FROM donations WHERE user_id = $userId ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Donation History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center text-primary mb-4">Your Donation History</h2>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>Donation ID</th>
                        <th>Meal Type</th>
                        <th>Food Quantity</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo ucfirst($row['meal_type']); ?></td>
                            <td><?php echo $row['food_quantity']; ?></td>
                            <td><?php echo $row['address'] . ', ' . $row['city'] . ', ' . $row['state']; ?></td>
                            <td>
                                <span class="badge bg-<?php
                                    echo ($row['donation_status'] === 'Pending') ? 'warning' :
                                         (($row['donation_status'] === 'Cancelled') ? 'danger' : 'success');
                                ?>">
                                    <?php echo $row['donation_status']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                            <td>
                                <?php if ($row['donation_status'] !== 'Cancelled'): ?>
                                    <a href="cancel_donation.php?donation_id=<?php echo $row['id']; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to cancel this donation?');">
                                       Cancel
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div id="msg" class="alert alert-info text-center">
            You haven't made any donations yet.
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="donate_meals.php" class="btn btn-success">Donate Now</a>
    </div>
</div>

<?php include 'footer.php'; ?>
<style>
    .table-responsive {
        margin-top: 100px;
    }
    #msg{
        margin-top: 100px;
        margin-bottom: 270px;
    }
</style>
</body>
</html>
