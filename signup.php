<?php require_once 'includes/header.php'; // Include Bootstrap header ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body p-4 p-md-5">
                    <h4 class="mb-3 fw-semibold" style="color: #ff8c00;">Become a Part of FoodShare</h4>
                    <p class="card-text mb-4">Select your role to get started:</p>
                    <form action="" method="GET" id="role-selection-form">
                        <div class="mb-4">
                            <label for="role-selection" class="form-label fw-bold">I want to...</label>
                            <select id="role-selection" name="role" class="form-select form-select-lg text-center border-2 border-warning" style="background-color: #fffbe6;" onchange="redirectToPage()">
                                <option value="" disabled selected>-- Select an Option --</option>
                                <option value="donor_register.php">Donate Food</option>
                                <option value="volunteer_register.php">Volunteer My Time</option>
                            </select>
                        </div>
                    </form>
                    <p class="mt-4 mb-0">Already have an account? <a href="login.php" class="fw-semibold" style="color: #ff8c00;">Login Here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple redirect function
    function redirectToPage() {
        const selectElement = document.getElementById('role-selection');
        const selectedValue = selectElement.value;
        if (selectedValue) {
            if (selectElement.selectedIndex > 0) {
                window.location.href = selectedValue;
            }
        }
    }
</script>

<?php require_once 'includes/footer.php'; // Include Bootstrap footer ?>