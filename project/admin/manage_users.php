<?php
include '../db/db.php'; // Adjust path if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { overflow-x: hidden; }
        .main-content { margin-left: 240px; padding: 20px; }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <h2 class="mb-4">Manage Users (Excluding Admins)</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Registered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT id, name, email, user_type, created_at FROM users WHERE user_type != 'admin' ORDER BY created_at DESC");
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['user_type']) ?></td>
                        <td><?= htmlspecialchars($row['created_at']) ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="deleteUser(<?= $row['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr><td colspan="6" class="text-center">No non-admin users found.</td></tr>
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

    function deleteUser(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            window.location.href = `delete_user.php?id=${userId}`;
        }
    }
</script>

</body>
</html>
