<?php
include './db/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = isset($_POST['user_id']) ? (int) $_POST['user_id'] : 0;

    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $mealType = mysqli_real_escape_string($conn, $_POST['meal_type']);
    $foodQty = (int) $_POST['food_quantity'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $landmark = mysqli_real_escape_string($conn, $_POST['landmark']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $status = "Pending";

    $sql = "INSERT INTO donations (
        user_id, first_name, last_name, phone, meal_type, food_quantity,
        address, city, landmark, state, donation_status
    ) VALUES (
        '$userId', '$firstName', '$lastName', '$phone', '$mealType', $foodQty,
        '$address', '$city', '$landmark', '$state', '$status'
    )";

    if (mysqli_query($conn, $sql)) {
        $donationId = mysqli_insert_id($conn);
    } else {
        die("Error inserting record: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Submitted</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="donation_status.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="main">
    <div class="container mt-5 text-center">
        <h2 class="text-success">Thank You for Donating!</h2>
        <p class="mt-3">Your donation has been submitted successfully.</p>
        <div class="card mx-auto mt-4" style="max-width: 400px;">
            <div class="card-body">
                <h5 class="card-title">Donation Details</h5>
                <p><strong>Donation ID:</strong> #<?php echo $donationId; ?></p>
                <p><strong>Meal Type:</strong> <?php echo ucfirst($mealType); ?></p>
                <p><strong>Status:</strong> <span class="badge bg-warning"><?php echo $status; ?></span></p>
            </div>
        </div>
        <a href="donate_meals.php" class="btn btn-primary mt-4">Donate Again</a>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
