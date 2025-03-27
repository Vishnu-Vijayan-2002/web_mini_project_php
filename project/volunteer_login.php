<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="volunteer_login.css">  <!-- External CSS -->
    <title>Volunteer Login</title>
</head>
<body>

<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="card p-5 shadow-lg" style="width: 400px;">
        <h3 class="text-center mb-4">Volunteer Login</h3>

        <form id="loginForm" onsubmit="return validateFormRecursive(0)">
            <div class="mb-3">
                <label class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                <input type="email" id="email" class="form-control" placeholder="Enter your email" required>
                <small id="emailError" class="text-danger"></small>
            </div>

            <div class="mb-3">
                <label class="form-label"><i class="fa-solid fa-lock"></i> Password</label>
                <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                <small id="passwordError" class="text-danger"></small>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="fa-solid fa-sign-in-alt"></i> Login
            </button>
        </form>

        <!-- Back to Home Link -->
        <div class="text-center mt-3">
            <a href="index.php" class="text-decoration-none">
                <i class="fa-solid fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="./js/volunteer_login.js"></script>  <!-- External JS for recursion validation -->
</body>
</html>
