<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="./images/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="./images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="donate_meals.css"> <!-- Custom CSS -->
    <title>Donate Meals - Feed People in Need</title>
</head>
<body>

<?php include 'header.php'; ?>

<!-- Donate Meals Form -->
<section class="donate-section container my-5">
    <h1 class="text-center mb-4">Donate Meals</h1>

    <form action="process_donation.php" method="POST" class="form-container p-4 rounded" onsubmit="return validateForm()">

        <!-- Donor Name Fields -->
        <div class="row g-3">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" 
                required pattern="[A-Za-z\s]+" title="Only letters allowed">
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" 
                required pattern="[A-Za-z\s]+" title="Only letters allowed">
            </div>
        </div>

        <!-- Phone -->
        <div class="mb-3 mt-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="phone" name="phone" placeholder="1234567890" 
            required pattern="\d{10}" title="Enter a valid 10-digit phone number">
        </div>

        <!-- Meal Type -->
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

        <!-- Food Quantity -->
        <div class="mb-3">
            <label for="food_quantity" class="form-label">Food Quantity (in servings)</label>
            <input type="number" class="form-control" id="food_quantity" name="food_quantity" placeholder="e.g., 50" 
            min="1" required>
        </div>

        <!-- Location Fields -->
        <h5 class="mb-3">Location Details</h5>

        <div class="row g-3">
            <div class="col-md-6">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" placeholder="123 Main St" required>
            </div>
            <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="New York" 
                required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
            </div>
        </div>

        <!-- Landmark & State Fields -->
        <div class="row g-3 mt-3">
            <div class="col-md-6">
                <label for="landmark" class="form-label">Landmark</label>
                <input type="text" class="form-control" id="landmark" name="landmark" placeholder="Near Central Park" 
                required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
            </div>
            <div class="col-md-6">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" placeholder="NY" 
                required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-success btn-lg w-100">Donate Now</button>
        </div>
    </form>
</section>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    // Additional form validation with JavaScript
    function validateForm() {
        const phone = document.getElementById("phone").value;
        const foodQuantity = document.getElementById("food_quantity").value;

        // Validate phone number (10 digits)
        const phonePattern = /^\d{10}$/;
        if (!phonePattern.test(phone)) {
            alert("Please enter a valid 10-digit phone number.");
            return false;
        }

        // Validate food quantity (must be greater than 0)
        if (foodQuantity <= 0) {
            alert("Food quantity must be at least 1 serving.");
            return false;
        }

        return true;
    }
</script>

</body>
</html>
