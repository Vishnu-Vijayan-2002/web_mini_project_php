<?php
ob_start();
include './db/db.php';

$openLogin = false;
$loginMsg = $registerMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Registration
    if (isset($_POST['register'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if ($password !== $confirm) {
            $registerMsg = "Passwords do not match!";
        } else {
            $check = $conn->prepare("SELECT id FROM users WHERE email=?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();
            
            if ($check->num_rows > 0) {
                $registerMsg = "Email already exists.";
            } else {
                $insert = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, 'donor')");
                $insert->bind_param("sss", $name, $email, $password);
                
                if ($insert->execute()) {
                    $registerMsg = "Registered successfully! Please login.";
                    $openLogin = true;
                } else {
                    $registerMsg = "Registration failed. Please try again.";
                }
            }
        }
    }

    // Login
    if (isset($_POST['login'])) {
        $email = trim($_POST['login_email']);
        $password = $_POST['login_password'];
        $user_type = $_POST['login_user_type'];
    
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND user_type=?");
        $stmt->bind_param("ss", $email, $user_type);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
    
            // ⚠️ Direct string comparison (Assuming plain text passwords in the DB)
            if ($password === $user['password']) {
    
                // ✅ Determine redirection page based on user type
                if ($user_type === 'volunteer') {
                    $redirectPage = 'volunteer_index.php?email=' . urlencode($email);
                } elseif ($user_type === 'admin') {
                    $redirectPage = 'admin/index.php';
                } else {
                    $redirectPage = 'index.php';
                }
    
                // ✅ Set localStorage and redirect
                echo "<script>
                    localStorage.setItem('user_id', '{$user['id']}');
                    localStorage.setItem('user_name', '{$user['name']}');
                    localStorage.setItem('user_type', '{$user['user_type']}');
                    localStorage.setItem('logged_in', 'true');
                    window.location.href = '{$redirectPage}';
                </script>";
                exit();
            } else {
                $loginMsg = "Invalid credentials!";
                $openLogin = true;
            }
        } else {
            $loginMsg = "Invalid credentials!";
            $openLogin = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login/Register - TheMeal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./signup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            max-width: 500px;
            margin: 50px auto;
        }
        .btn_close {
            float: right;
            cursor: pointer;
        }
        .alert {
            margin: 20px auto;
            max-width: 500px;
        }
    </style>
</head>
<body>

<!-- Display messages -->
<?php if (!empty($loginMsg) && $loginMsg !== "success"): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($loginMsg) ?>
    </div>
<?php endif; ?>

<?php if (!empty($registerMsg)): ?>
    <div class="alert alert-<?= strpos($registerMsg, 'success') !== false ? 'success' : 'danger' ?>">
        <?= htmlspecialchars($registerMsg) ?>
    </div>
<?php endif; ?>
<!-- LOGIN FORM -->
<div id="loginForm" class="form-container" style="<?= $openLogin ? '' : 'display: none;' ?>">
    <i class="fa-solid fa-xmark btn_close" data-bs-dismiss="modal" style="color: black;"></i>
    <h3 class="text-center">Login</h3>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">User Type</label>
            <select name="login_user_type" class="form-control" required>
                <option value="donor">Donor</option>
                <option value="volunteer">Volunteer</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="login_email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="login_password" class="form-control" placeholder="Enter password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
    </form>
    <p class="mt-3 text-center">
        Don't have an account? <a href="#" id="showRegister">Register here</a>
    </p>
</div>

<!-- REGISTER FORM -->
<div id="registerForm" class="form-container" style="<?= $openLogin ? 'display: none;' : '' ?>">
    <i class="fa-solid fa-xmark btn_close" data-bs-dismiss="modal" style="color: black;"></i>
    <h3 class="text-center">Register</h3>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Confirm password" required>
        </div>
        <button type="submit" name="register" class="btn btn-success w-100">Register</button>
    </form>
    <p class="mt-3 text-center">
        Already have an account? <a href="#" id="showLogin">Login here</a>
    </p>
</div>

<script>
    document.getElementById("showRegister").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("loginForm").style.display = "none";
        document.getElementById("registerForm").style.display = "block";
    });

    document.getElementById("showLogin").addEventListener("click", function(e) {
        e.preventDefault();
        document.getElementById("registerForm").style.display = "none";
        document.getElementById("loginForm").style.display = "block";
    });

    // Auto-switch to login form after successful registration
    <?php if ($openLogin): ?>
        document.getElementById("registerForm").style.display = "none";
        document.getElementById("loginForm").style.display = "block";
    <?php endif; ?>
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
