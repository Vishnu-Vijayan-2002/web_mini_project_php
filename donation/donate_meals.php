<!-- donate_meals.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="./images/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="./images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="donate_meals.css">
    <title>Donate Meals - Feed People in Need</title>
</head>
<body>

<?php include 'header.php'; ?>

<section id="section" class="donate-section container my-5">
    <h1 class="text-center mb-4">Donate Meals</h1>
    <form action="donation_status.php" method="POST" class="form-container p-4 rounded shadow" onsubmit="return validateForm()">
        <input type="hidden" id="user_id" name="user_id"> <!-- set from localStorage -->

        <div class="row g-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required pattern="[A-Za-z\s]+" title="Only letters allowed">
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required pattern="[A-Za-z\s]+" title="Only letters allowed">
            </div>
        </div>

        <div class="mb-3 mt-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" required pattern="\d{10}" title="Enter a valid 10-digit phone number">
        </div>

        <div class="mb-3">
            <label for="meal_type" class="form-label">Meal Type</label>
            <select class="form-select" id="meal_type" name="meal_type" required>
                <option value="" disabled selected>Select Meal Type</option>
                <option value="breakfast">Breakfast</option>
                <option value="lunch">Lunch</option>
                <option value="dinner">Dinner</option>
                <option value="snack">Snack</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="food_quantity" class="form-label">Food Quantity (in servings)</label>
            <input type="number" class="form-control" id="food_quantity" name="food_quantity" min="1" required>
        </div>

        <h5 class="mb-3">Location Details</h5>
        <div class="row g-3">
            <div class="col-md-6">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
            </div>
        </div>

        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <label for="landmark" class="form-label">Landmark</label>
                <input type="text" class="form-control" id="landmark" name="landmark" required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
            </div>
            <div class="col-md-6">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
            </div>
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg w-100">Donate Now</button>
        </div>
    </form>
</section>

<?php include 'footer.php'; ?>

<script>
function validateForm() {
    const namePattern = /^[A-Za-z\s]+$/;
    const phonePattern = /^\d{10}$/;
    const firstName = document.getElementById("first_name").value.trim();
    const lastName = document.getElementById("last_name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const quantity = document.getElementById("food_quantity").value;
    const city = document.getElementById("city").value.trim();
    const landmark = document.getElementById("landmark").value.trim();
    const state = document.getElementById("state").value.trim();

    if (!localStorage.getItem("user_id")) {
        alert("Please login to donate.");
        return false;
    }

    if (!namePattern.test(firstName) || !namePattern.test(lastName)) {
        alert("Please enter valid names.");
        return false;
    }

    if (!phonePattern.test(phone)) {
        alert("Enter a valid 10-digit phone number.");
        return false;
    }

    if (quantity <= 0) {
        alert("Enter a valid food quantity.");
        return false;
    }

    if (!namePattern.test(city) || !namePattern.test(landmark) || !namePattern.test(state)) {
        alert("Enter valid location details.");
        return false;
    }

    // ✅ Set user_id from localStorage
    document.getElementById("user_id").value = localStorage.getItem("user_id");
    return true;
}
</script>
</body>
</html>
