<?php
include 'includes/db_connect.php';

// Authentication Check: Ensure user is logged in and is a donor
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'donor') {
    header("Location: login.php");
    exit;
}

$donor_id = $_SESSION["user_id"];
$errors = [];
$success_message = '';

// --- Handle New Donation Form Submission ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_donation'])) {
    $food_description = trim($_POST['food_description'] ?? '');
    $quantity = trim($_POST['quantity'] ?? '');
    $pickup_address = trim($_POST['pickup_address'] ?? '');
    $pickup_time_preference = trim($_POST['pickup_time_preference'] ?? '');

    // Basic Validation
    if (empty($food_description)) $errors[] = "Food description is required.";
    if (empty($pickup_address)) $errors[] = "Pickup address is required.";
    // Add more validation...

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO donations (donor_id, food_description, quantity, pickup_address, pickup_time_preference, status)
                    VALUES (:donor_id, :food_description, :quantity, :pickup_address, :pickup_time_preference, 'pending')";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':donor_id', $donor_id);
            $stmt->bindParam(':food_description', $food_description);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':pickup_address', $pickup_address);
            $stmt->bindParam(':pickup_time_preference', $pickup_time_preference);

            if ($stmt->execute()) {
                $success_message = "Donation listed successfully!";
            } else {
                $errors[] = "Failed to list donation. Please try again.";
            }
        } catch (PDOException $e) {
            error_log("Donation submission failed: " . $e->getMessage());
            $errors[] = "An error occurred. Please try again later.";
        }
    }
}

// --- Fetch Donor's Existing Donations ---
$donations = [];
try {
    $stmt = $pdo->prepare("SELECT donation_id, food_description, quantity, status, created_at, assigned_volunteer_id FROM donations WHERE donor_id = :donor_id ORDER BY created_at DESC");
    $stmt->bindParam(':donor_id', $donor_id);
    $stmt->execute();
    $donations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetching donor donations failed: " . $e->getMessage());
    // Display an error message on the page if needed
}

// --- Fetch Donor's Default Address (Optional Prefill) ---
$default_address = '';
try {
     $stmt = $pdo->prepare("SELECT address, city, postal_code FROM users WHERE user_id = :donor_id");
     $stmt->bindParam(':donor_id', $donor_id);
     $stmt->execute();
     $user_info = $stmt->fetch(PDO::FETCH_ASSOC);
     if ($user_info) {
        $addr_parts = array_filter([$user_info['address'], $user_info['city'], $user_info['postal_code']]);
        $default_address = implode(', ', $addr_parts);
     }
} catch (PDOException $e) {
     error_log("Fetching donor address failed: " . $e->getMessage());
}

// --- BEGIN: Donor Profile Edit Logic ---
$profile_success_message = '';
$profile_errors = [];
$profile_user_data = [];

// Show message from session if redirected
if (isset($_SESSION['profile_success_message'])) {
    $profile_success_message = $_SESSION['profile_success_message'];
    unset($_SESSION['profile_success_message']);
}
if (isset($_SESSION['profile_errors'])) {
    $profile_errors = $_SESSION['profile_errors'];
    unset($_SESSION['profile_errors']);
}

try {
    $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone, address FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $donor_id);
    $stmt->execute();
    $profile_user_data = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $profile_errors[] = "Error fetching user data.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_profile_submit'])) {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($first_name)) $profile_errors[] = "First name is required";
    if (empty($phone)) $profile_errors[] = "Phone number is required";

    // Check if any field actually changed
    $changed = (
        $first_name !== ($profile_user_data['first_name'] ?? '') ||
        $last_name !== ($profile_user_data['last_name'] ?? '') ||
        $phone !== ($profile_user_data['phone'] ?? '') ||
        $address !== ($profile_user_data['address'] ?? '')
    );

    if (empty($profile_errors)) {
        if ($changed) {
            try {
                $sql = "UPDATE users SET 
                        first_name = :first_name,
                        last_name = :last_name,
                        phone = :phone,
                        address = :address
                        WHERE user_id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':phone' => $phone,
                    ':address' => $address,
                    ':user_id' => $donor_id
                ]);
                $profile_success_message = "Profile updated successfully!";
                $_SESSION['first_name'] = $first_name;

                // Refresh user data
                $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone, address FROM users WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $donor_id);
                $stmt->execute();
                $profile_user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $profile_errors[] = "Error updating profile: " . $e->getMessage();
            }
        } else {
            $profile_success_message = "No changes made to your profile.";
        }
    } else {
        $profile_user_data['first_name'] = $first_name;
        $profile_user_data['last_name'] = $last_name;
        $profile_user_data['phone'] = $phone;
        $profile_user_data['address'] = $address;
    }

    // Store messages in session and redirect (PRG pattern)
    $_SESSION['profile_success_message'] = $profile_success_message;
    $_SESSION['profile_errors'] = $profile_errors;
    header("Location: donor_dashboard.php#editProfileModal");
    exit;
}
// --- END: Donor Profile Edit Logic ---

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donor Dashboard - FoodShare Connect</title>
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" sizes="any" type="image/svg+xml" href="./images/Fav.svg">
    <style>
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .container-fluid {
            flex: 1;
        }
        .row {
            min-height: 100%;
            flex: 1;
        }
        .donor-sidebar {
            background: #f8fafc;
            border-right: 1px solid #e3e6f0;
            padding-bottom: 20px;
            height: 100%;
        }
        @media (min-width: 768px) {
            .donor-sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                z-index: 1000;
                overflow-y: auto;
            }
        }
    </style>
</head>
<body>
<!-- Donor Header -->
<nav class="navbar navbar-expand-lg bg-light shadow-sm sticky-top">
    <div class="container-fluid" style="padding-left: 0; padding-right: 0; display: flex; flex-wrap: inherit; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; padding-left: 1rem;">
            <a class="navbar-brand" href="index.php" style="padding-top: 0; padding-bottom: 0; margin-right: 0.5rem;">
                <img src="./images/FoodShare_logo.svg" alt="FoodShare Connect Logo" style="height: 80px; width: auto; vertical-align: middle;">
            </a>
            <span class="navbar-text" style="font-size: 1.25rem; font-weight: 700; vertical-align: middle;">
                Donor Portal
            </span>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#donorNav" aria-controls="donorNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="donorNav">
            <ul class="navbar-nav mb-2 mb-lg-0" style="margin-left: 1.5rem;">
                <li class="nav-item">
                    <a class="nav-link" href="#new-donation" style="font-size: 1.05rem;">List Donation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#donation-history" style="font-size: 1.05rem;">Donation History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php#faqs" style="font-size: 1.05rem;">FAQs</a>
                </li>
            </ul>
            <ul class="navbar-nav" style="margin-left: auto; padding-right: 1rem;">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 1.05rem;">
                        <i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($_SESSION['first_name']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="donor_dashboard.php">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="page-wrapper">
    <div class="content-wrapper">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <!-- Sidebar -->
                <nav class="col-lg-2 col-md-3 d-none d-md-block donor-sidebar px-0">
                    <div class="d-flex flex-column pt-4 pb-2 px-3 h-100">
                        <h4 class="mb-4" style="color:#198754;"><i class="bi bi-gift-fill me-2"></i>Donor</h4>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item mb-2">
                                <a href="donor_dashboard.php" id="sidebar-dashboard" class="nav-link" style="font-weight:500;">
                                    <i class="bi bi-house-door me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#new-donation" id="sidebar-new-donation" class="nav-link">
                                    <i class="bi bi-plus-circle me-2"></i> List Donation
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#donation-history" id="sidebar-donation-history" class="nav-link">
                                    <i class="bi bi-list-task me-2"></i> Donation History
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <!-- Replace link with modal trigger button -->
                                <button type="button" class="nav-link btn btn-link text-start w-100" id="sidebar-edit-profile" data-bs-toggle="modal" data-bs-target="#editProfileModal" style="text-align:left;">
                                    <i class="bi bi-person-gear me-2"></i> Edit Profile
                                </button>
                            </li>
                            <li class="nav-item mt-4">
                                <a href="logout.php" class="nav-link text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- Main Content Area -->
                <main class="col-lg-10 col-md-9 ms-sm-auto px-md-4 py-4">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-3 mb-4 border-bottom">
                        <h2 class="mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>! <span class="badge" style="background:#198754;color:#fff;">Donor</span></h2>
                    </div>
                    <!-- Display Feedback Messages -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>
                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- List New Donation Form Card -->
                    <div class="card shadow-sm mb-4" id="new-donation">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="bi bi-gift-fill me-2"></i>List a New Food Donation</h5>
                        </div>
                        <div class="card-body">
                            <form action="donor_dashboard.php" method="POST">
                                <div class="mb-3">
                                    <label for="food_description" class="form-label">Food Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="food_description" name="food_description" rows="3" required placeholder="e.g., 5 Sandwiches (Chicken), 2 Boxes Pasta (uncooked), 1 Tray Lasagna (cooked today) - Include type, quantity, allergens, condition..."></textarea>
                                    <div class="form-text">Be descriptive! This helps volunteers understand the donation.</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="quantity" class="form-label">Quantity Estimate</label>
                                        <input type="text" class="form-control" id="quantity" name="quantity" placeholder="e.g., 5 meals, 1 box, 10 kg">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="pickup_time_preference" class="form-label">Pickup Time Preference</label>
                                        <input type="text" class="form-control" id="pickup_time_preference" name="pickup_time_preference" placeholder="e.g., Weekdays 9am-5pm, Anytime today">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="pickup_address" class="form-label">Full Pickup Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="pickup_address" name="pickup_address" rows="2" required><?php echo htmlspecialchars($default_address); ?></textarea>
                                    <div class="form-text">Provide the complete address where the volunteer should collect the food.</div>
                                </div>
                                <button type="submit" name="submit_donation" class="btn btn-success"><i class="bi bi-check-circle-fill me-2"></i>Submit Donation</button>
                            </form>
                        </div>
                    </div>

                    <!-- View Existing Donations Card -->
                    <div class="card shadow-sm" id="donation-history">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="bi bi-list-task me-2"></i>Your Donation History</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($donations)): ?>
                                <p class="text-center text-muted p-3">You have not listed any donations yet.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0 align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th scope="col">Description</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Listed On</th>
                                                <th scope="col">Status</th>
                                                <th scope="col" class="text-center">Volunteer</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($donations as $donation):
                                                $status_class = 'secondary';
                                                switch ($donation['status']) {
                                                    case 'pending': $status_class = 'warning text-dark'; break;
                                                    case 'assigned': $status_class = 'info text-dark'; break;
                                                    case 'collected': $status_class = 'primary'; break;
                                                    case 'delivered': $status_class = 'success'; break;
                                                    case 'cancelled': $status_class = 'danger'; break;
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(mb_strimwidth($donation['food_description'], 0, 60, "...")); ?></td>
                                                <td><?php echo htmlspecialchars($donation['quantity'] ?: '-'); ?></td>
                                                <td><small><?php echo date("M d, Y H:i", strtotime($donation['created_at'])); ?></small></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $status_class; ?>"><?php echo ucfirst(htmlspecialchars($donation['status'])); ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($donation['assigned_volunteer_id']): ?>
                                                        <i class="bi bi-person-check-fill text-success" title="Volunteer Assigned"></i>
                                                    <?php else: ?>
                                                        <i class="bi bi-hourglass-split text-muted" title="Waiting for Volunteer"></i>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="py-3 border-top bg-light">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 text-muted small">
                    FoodShare &copy; <?php echo date("Y"); ?>
                </div>
                <div class="col-md-6 text-end d-none d-md-block">
                    <a href="index.php#faqs" class="text-decoration-none text-muted me-3">FAQs</a>
                    <a href="#" class="text-decoration-none text-muted me-3">Contact</a>
                    <a href="#" class="text-decoration-none text-muted me-3">Terms</a>
                    <a href="#" class="text-decoration-none text-muted">Privacy</a>
                </div>
            </div>
        </div>
    </footer>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar active state logic
(function() {
    // Remove all active styles
    function clearSidebarActive() {
        document.querySelectorAll('.donor-sidebar .nav-link').forEach(function(link) {
            link.classList.remove('active');
            link.style.background = '';
            link.style.color = '';
        });
    }
    // Set active style
    function setSidebarActive(id) {
        var link = document.getElementById(id);
        if (link) {
            link.classList.add('active');
            link.style.background = '#198754';
            link.style.color = '#fff';
        }
    }
    // Determine which sidebar item to highlight
    function updateSidebarActive() {
        clearSidebarActive();
        var hash = window.location.hash;
        if (window.location.pathname.endsWith('donor_dashboard.php') && (!hash || hash === '#')) {
            setSidebarActive('sidebar-dashboard');
        } else if (hash === '#new-donation') {
            setSidebarActive('sidebar-new-donation');
        } else if (hash === '#donation-history') {
            setSidebarActive('sidebar-donation-history');
        } else if (window.location.pathname.includes('edit_donor_profile.php')) {
            setSidebarActive('sidebar-edit-profile');
        } else {
            setSidebarActive('sidebar-dashboard');
        }
    }
    window.addEventListener('DOMContentLoaded', updateSidebarActive);
    window.addEventListener('hashchange', updateSidebarActive);
})();
</script>

<!-- Add Edit Profile Modal at the end of body -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header" style="background:#198754;color:#fff;">
          <h5 class="modal-title" id="editProfileModalLabel"><i class="bi bi-person-gear me-2"></i>Edit Profile</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php if ($profile_success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($profile_success_message); ?></div>
          <?php endif; ?>
          <?php if (!empty($profile_errors)): ?>
            <div class="alert alert-danger">
              <ul class="mb-0">
                <?php foreach ($profile_errors as $error): ?>
                  <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">First Name</label>
              <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($profile_user_data['first_name'] ?? ''); ?>" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">Last Name</label>
              <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($profile_user_data['last_name'] ?? ''); ?>">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="tel" name="phone" class="form-control" value="<?php echo htmlspecialchars($profile_user_data['phone'] ?? ''); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($profile_user_data['address'] ?? ''); ?></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>Close</button>
          <button type="submit" name="edit_profile_submit" class="btn btn-success"><i class="bi bi-save me-2"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php if ($profile_success_message || !empty($profile_errors)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    editProfileModal.show();
});
</script>
<?php endif; ?>

</body>
</html>