<!-- donate_meals.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="./images/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="./images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="donate_meals.css">
    <title>Donate Meals - Feed People in Need</title>
</head>
<style>
    /* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background: linear-gradient(135deg, #e0f7fa, #81c784);
    color: #333;
    line-height: 1.6;
}

/* Section Styling */
.donate-section {
    background: #ffffff;
    border-radius: 20px;
    padding: 50px;
    max-width: 900px;
    margin: 80px auto;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    position: relative;
   
}

.donate-section h1 {
    font-size: 2.8rem;
    font-weight: 600;
    color: #00796b;
    margin-bottom: 30px;
    display: inline-block;
    letter-spacing: 1px;
    text-align: center; 
    width: 100%; 
}

h1{
    text-align: center;
}

/* Form Container */
.form-container {
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 40px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
}

/* Hover effect for form container */
.form-container:hover {
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

/* Labels */
.form-label {
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
    font-size: 1rem;
}

/* Inputs & Selects */
.form-control,
.form-select {
    padding: 14px 18px;
    font-size: 1rem;
    border-radius: 10px;
    border: 1px solid #bdbdbd;
    background-color: #ffffff;
    transition: all 0.2s ease;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

.form-control:focus,
.form-select:focus {
    border-color: #00796b;
    box-shadow: 0 0 8px rgba(0, 121, 107, 0.3);
    outline: none;
}

/* Remove red border on :invalid */
.form-control:invalid,
.form-select:invalid {
    border-color: #ced4da;
    box-shadow: none;
}

/* Submit Button */
button[type="submit"] {
    background: linear-gradient(135deg, #00796b, #004d40);
    border: none;
    color: white;
    font-size: 1.1rem;
    padding: 16px;
    border-radius: 10px;
    cursor: pointer;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    width: 100%;
    letter-spacing: 1px;
}

button[type="submit"]:hover {
    background: linear-gradient(135deg, #004d40, #00796b);
    box-shadow: 0 8px 15px rgba(0, 121, 107, 0.3);
    transform: translateY(-3px);
}

/* Form Section Subheading */
.section-subtitle {
    font-size: 1.2rem;
    font-weight: 600;
    color: #00796b;
    margin-bottom: 20px;
    text-align: left;
}

/* Responsive */
@media (max-width: 768px) {
    .donate-section {
        padding: 30px;
    }

    .form-container {
        padding: 25px;
    }

    .donate-section h1 {
        font-size: 2.2rem;
    }
}
</style>
<body>

<?php include 'header.php'; ?>

<section class="donate-section">
    <h1>Donate Meals</h1>
    <form action="donation_status.php" method="POST" class="form-container" onsubmit="return validateForm()">
        <input type="hidden" id="user_id" name="user_id">

        <div class="row">
            <div class="col-md-6">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required pattern="[A-Za-z\s]+" title="Only letters allowed">
            </div>
            <div class="col-md-6">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required pattern="[A-Za-z\s]+" title="Only letters allowed">
            </div>
        </div>

        <div class="mb-3">
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

        <h5 class="section-subtitle">Location Details</h5>
        <div class="row">
            <div class="col-md-6">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required pattern="[A-Za-z\s]+" title="Only letters and spaces allowed">
            </div>
        </div>

        <div class="row mt-3">
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
            <button type="submit">Donate Now</button>
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
        alert("Please log in to continue.");
        window.location.href = "login.php";
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

    document.getElementById("user_id").value = localStorage.getItem("user_id");
    return true;
}
</script>

</body>
</html>
