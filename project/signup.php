<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./signup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div id="loginForm" class="form-container">
<i class="fa-solid fa-xmark btn_close" data-bs-dismiss="modal" style="color: black;"></i>
    <h3 class="text-center">Login</h3>
    <form>
    <div class="mb-3">
            <label class="form-label">User Type</label>
            <select name="user_type" class="form-control" required>
                <option value="volunteer">Volunteer</option>
                <option value="donor">Donor</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" placeholder="Enter password" required>
        </div>
        <button class="btn btn-primary w-100">Login</button>
    </form>
    <p class="mt-3 text-center">
        Don't have an account? <a href="#" id="showRegister">Register here</a>
    </p>
</div>

<!-- REGISTER FORM (Hidden by Default) -->
<div id="registerForm" class="form-container" style="display: none;">
<i class="fa-solid fa-xmark btn_close" data-bs-dismiss="modal" style="color: black;"></i>
    <h3 class="text-center">Register</h3>
    <form>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" placeholder="Enter name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" class="form-control" placeholder="Enter password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm password" required>
        </div>
        <button class="btn btn-success w-100">Register</button>
    </form>
    <p class="mt-3 text-center">
        Already have an account? <a href="#" id="showLogin">Login here</a>
    </p>
</div>
</body>
<script>
    document.getElementById("showRegister").addEventListener("click", function() {
        document.getElementById("loginForm").style.display = "none";
        document.getElementById("registerForm").style.display = "block";
    });

    document.getElementById("showLogin").addEventListener("click", function() {
        document.getElementById("registerForm").style.display = "none";
        document.getElementById("loginForm").style.display = "block";
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>