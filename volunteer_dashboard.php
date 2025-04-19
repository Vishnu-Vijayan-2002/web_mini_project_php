<?php
include 'includes/db_connect.php';

// Authentication Check: Ensure user is logged in, is a volunteer, AND approved
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'volunteer') {
    header("Location: login.php"); // Not logged in or not a volunteer
    exit;
}
// Check approval status after ensuring they are a volunteer
try {
    $stmt = $pdo->prepare("SELECT is_approved FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user || $user['is_approved'] != 1) {
        // Log them out or show a specific "pending approval" message page
        session_destroy(); // Log them out if not approved
        header("Location: login.php?status=pending"); // Redirect with a status message
        exit;
    }
} catch (PDOException $e) {
    error_log("Volunteer approval check failed: " . $e->getMessage());
    // Handle error, maybe redirect to login with a generic error
    header("Location: login.php?status=error");
    exit;
}

$volunteer_id = $_SESSION["user_id"];
$errors = [];
$success_message = '';

// --- Handle Accepting a Task ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_task'])) {
    $donation_id_to_accept = $_POST['donation_id'] ?? null;

    if ($donation_id_to_accept) {
        try {
            $pdo->beginTransaction();

            // Check if donation is still pending and not assigned
            $stmt_check = $pdo->prepare("SELECT status, assigned_volunteer_id FROM donations WHERE donation_id = :donation_id");
            $stmt_check->bindParam(':donation_id', $donation_id_to_accept);
            $stmt_check->execute();
            $donation_status = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($donation_status && $donation_status['status'] === 'pending' && is_null($donation_status['assigned_volunteer_id'])) {
                // Assign the donation to the current volunteer
                $sql_assign = "UPDATE donations SET status = 'assigned', assigned_volunteer_id = :volunteer_id WHERE donation_id = :donation_id AND status = 'pending'"; // Double check status
                $stmt_assign = $pdo->prepare($sql_assign);
                $stmt_assign->bindParam(':volunteer_id', $volunteer_id);
                $stmt_assign->bindParam(':donation_id', $donation_id_to_accept);

                if ($stmt_assign->execute() && $stmt_assign->rowCount() > 0) {
                    $pdo->commit();
                    $success_message = "Task accepted successfully! Please coordinate pickup.";
                } else {
                    $pdo->rollBack();
                    $errors[] = "Could not accept task. It might have been taken by another volunteer or an error occurred.";
                }
            } else {
                $pdo->rollBack();
                $errors[] = "This task is no longer available or already assigned.";
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            error_log("Accept task failed: " . $e->getMessage());
            $errors[] = "An error occurred while accepting the task.";
        }
    } else {
        $errors[] = "Invalid request.";
    }
}

// --- Handle Marking as Collected/Delivered (Add similar POST handling) ---
// Example: Mark as Collected
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_collected'])) {
    $donation_id_to_update = $_POST['donation_id'] ?? null;
    if ($donation_id_to_update) {
        try {
            $sql = "UPDATE donations SET status = 'collected', collection_time = NOW()
                    WHERE donation_id = :donation_id AND assigned_volunteer_id = :volunteer_id AND status = 'assigned'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':donation_id', $donation_id_to_update);
            $stmt->bindParam(':volunteer_id', $volunteer_id);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $success_message = "Donation marked as collected!";
            } else {
                $errors[] = "Could not mark as collected. Status might have changed or it's not assigned to you.";
            }
        } catch (PDOException $e) {
            error_log("Mark collected failed: " . $e->getMessage());
            $errors[] = "An error occurred.";
        }
    }
}
// Example: Mark as Delivered
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_delivered'])) {
    $donation_id_to_update = $_POST['donation_id'] ?? null;
    if ($donation_id_to_update) {
        try {
            $sql = "UPDATE donations SET status = 'delivered', delivery_time = NOW()
                    WHERE donation_id = :donation_id AND assigned_volunteer_id = :volunteer_id AND status = 'collected'";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':donation_id', $donation_id_to_update);
            $stmt->bindParam(':volunteer_id', $volunteer_id);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $success_message = "Donation marked as delivered! Thank you!";
            } else {
                $errors[] = "Could not mark as delivered. Status must be 'collected' and assigned to you.";
            }
        } catch (PDOException $e) {
            error_log("Mark delivered failed: " . $e->getMessage());
            $errors[] = "An error occurred.";
        }
    }
}

// --- Fetch Available Tasks (Pending Donations) ---
$available_tasks = [];
try {
    $stmt = $pdo->prepare("
        SELECT d.donation_id, d.food_description, d.quantity, d.pickup_address, d.pickup_time_preference, d.created_at, u.first_name AS donor_name
        FROM donations d
        JOIN users u ON d.donor_id = u.user_id
        WHERE d.status = 'pending'
        ORDER BY d.created_at ASC
    ");
    $stmt->execute();
    $available_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Fetching available tasks failed: " . $e->getMessage());
    $errors[] = "Could not load available tasks.";
}

// --- Fetch Volunteer's Current/Completed Tasks ---
$my_tasks = [];
try {
    $stmt = $pdo->prepare("
        SELECT d.donation_id, d.food_description, d.quantity, d.pickup_address, d.pickup_time_preference, d.status, d.created_at, d.collection_time, d.delivery_time, u.first_name AS donor_name, u.phone AS donor_phone
        FROM donations d
        JOIN users u ON d.donor_id = u.user_id
        WHERE d.assigned_volunteer_id = :volunteer_id
        ORDER BY FIELD(d.status, 'assigned', 'collected', 'delivered', 'cancelled'), d.created_at DESC
    "); // Order by status progression
    $stmt->bindParam(':volunteer_id', $volunteer_id);
    $stmt->execute();
    $my_tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
catch (PDOException $e) {
    error_log("Fetching volunteer tasks failed: " . $e->getMessage());
    $errors[] = "Could not load your tasks.";
}

// --- BEGIN: Volunteer Profile Edit Logic ---
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
    $stmt->bindParam(':user_id', $volunteer_id);
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
                    ':user_id' => $volunteer_id
                ]);
                $profile_success_message = "Profile updated successfully!";
                $_SESSION['first_name'] = $first_name;

                // Refresh user data
                $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone, address FROM users WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $volunteer_id);
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
    header("Location: volunteer_dashboard.php#editProfileModal");
    exit;
}
// --- END: Volunteer Profile Edit Logic ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Dashboard - FoodShare Connect</title>
    
    <!-- Bootstrap CSS -->
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
        .volunteer-sidebar {
            background: #f8fafc;
            border-right: 1px solid #e3e6f0;
            padding-bottom: 20px;
            height: 100%;
        }
        @media (min-width: 768px) {
            .volunteer-sidebar {
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
<!-- Volunteer Header -->
<nav class="navbar navbar-expand-lg bg-light shadow-sm sticky-top">
    <div class="container-fluid" style="padding-left: 0; padding-right: 0; display: flex; flex-wrap: inherit; align-items: center; justify-content: space-between;">

        <div style="display: flex; align-items: center; padding-left: 1rem;"> <a class="navbar-brand" href="index.php" style="padding-top: 0; padding-bottom: 0; margin-right: 0.5rem;">
                <img src="./images/FoodShare_logo.svg" alt="FoodShare Connect Logo" style="height: 80px; width: auto; vertical-align: middle;">
            </a>
            <span class="navbar-text" style="font-size: 1.25rem; font-weight: 700; vertical-align: middle;">
                 Volunteer Portal
            </span>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#volunteerNav" aria-controls="volunteerNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="volunteerNav">

            <ul class="navbar-nav mb-2 mb-lg-0" style="margin-left: 1.5rem;">
                <li class="nav-item">
                    <a class="nav-link" href="#available-tasks" style="font-size: 1.05rem;">Available Tasks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#my-tasks" style="font-size: 1.05rem;">My Tasks</a>
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
                        <li><a class="dropdown-item" href="volunteer_dashboard.php">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a></li>
                    </ul>
                </li>
             </ul>
        </div></div></nav>


<div class="page-wrapper">
    <!-- Main Content Container -->
    <div class="content-wrapper">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <!-- Sidebar -->
                <nav class="col-lg-2 col-md-3 d-none d-md-block volunteer-sidebar px-0">
                    <div class="d-flex flex-column pt-4 pb-2 px-3 h-100">
                        <h4 class="mb-4" style="color:#ff8c00;"><i class="bi bi-person-heart me-2"></i>Volunteer</h4>
                        <ul class="nav nav-pills flex-column mb-auto">
                            <li class="nav-item mb-2">
                                <a href="volunteer_dashboard.php" id="sidebar-dashboard" class="nav-link" style="font-weight:500;">
                                    <i class="bi bi-house-door me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#available-tasks" id="sidebar-available-tasks" class="nav-link">
                                    <i class="bi bi-search me-2"></i> Available Tasks
                                </a>
                            </li>
                            <li class="nav-item mb-2">
                                <a href="#my-tasks" id="sidebar-my-tasks" class="nav-link">
                                    <i class="bi bi-person-check-fill me-2"></i> My Tasks
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
                        <h2 class="mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>! <span class="badge" style="background:#ff8c00;color:#fff;">Volunteer</span></h2>
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

                    <!-- Available Tasks -->
                    <div class="card shadow-sm mb-4" id="available-tasks">
                        <div class="card-header" style="background:#ff8c00;color:#fff;">
                            <h5 class="mb-0"><i class="bi bi-search me-2"></i>Available Donation Pickups</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($available_tasks)): ?>
                                <p class="text-center text-muted p-3">No pending donations available right now. Check back later!</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0 align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Description</th>
                                                <th>Qty</th>
                                                <th>Location (General)</th>
                                                <th>Listed</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($available_tasks as $task): ?>
                                                <tr>
                                                    <td title="<?php echo htmlspecialchars($task['food_description']); ?>">
                                                        <?php echo htmlspecialchars(mb_strimwidth($task['food_description'], 0, 50, "...")); ?>
                                                        <small class="d-block text-muted">From: <?php echo htmlspecialchars($task['donor_name']); ?></small>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($task['quantity'] ?: '-'); ?></td>
                                                    <td>
                                                        <?php echo htmlspecialchars($task['pickup_address'] ?: 'N/A'); ?>
                                                        <small class="d-block text-muted">Pref: <?php echo htmlspecialchars($task['pickup_time_preference'] ?: 'Any'); ?></small>
                                                    </td>
                                                    <td><small><?php echo date("M d, Y H:i", strtotime($task['created_at'])); ?></small></td>
                                                    <td class="text-center">
                                                        <form action="volunteer_dashboard.php" method="POST" style="display: inline;">
                                                            <input type="hidden" name="donation_id" value="<?php echo $task['donation_id']; ?>">
                                                            <button type="submit" name="accept_task" class="btn btn-success btn-sm" title="Accept Task">
                                                                <i class="bi bi-check-lg me-1"></i> Accept
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- My Assigned/Completed Tasks -->
                    <div class="card shadow-sm mb-4" id="my-tasks">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>Your Assigned & Completed Tasks</h5>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($my_tasks)): ?>
                                <p class="text-center text-muted p-3">You have not accepted any tasks yet.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm mb-0 align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Description</th>
                                                <th>Donor Info</th>
                                                <th>Pickup Address</th>
                                                <th>Status</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($my_tasks as $task):
                                                // Determine badge color based on status
                                                $status_style = '';
                                                switch ($task['status']) {
                                                    case 'pending': $status_style = 'background:#ffc107;color:#212529;'; break;
                                                    case 'assigned': $status_style = 'background:#ff8c00;color:#fff;'; break;
                                                    case 'collected': $status_style = 'background:#0d6efd;color:#fff;'; break;
                                                    case 'delivered': $status_style = 'background:#198754;color:#fff;'; break;
                                                    case 'cancelled': $status_style = 'background:#dc3545;color:#fff;'; break;
                                                    default: $status_style = 'background:#6c757d;color:#fff;';
                                                }
                                            ?>
                                                <tr>
                                                    <td title="<?php echo htmlspecialchars($task['food_description']); ?>">
                                                        <?php echo htmlspecialchars(mb_strimwidth($task['food_description'], 0, 45, "...")); ?>
                                                        <small class="d-block text-muted">Qty: <?php echo htmlspecialchars($task['quantity'] ?: '-'); ?></small>
                                                    </td>
                                                    <td>
                                                        <?php echo htmlspecialchars($task['donor_name']); ?>
                                                        <small class="d-block text-muted"><i class="bi bi-telephone-fill me-1"></i><?php echo htmlspecialchars($task['donor_phone'] ?: 'N/A'); ?></small>
                                                    </td>
                                                    <td title="<?php echo htmlspecialchars($task['pickup_address']); ?>">
                                                        <?php echo htmlspecialchars(mb_strimwidth($task['pickup_address'], 0, 40, "...")); ?>
                                                        <small class="d-block text-muted">Pref: <?php echo htmlspecialchars($task['pickup_time_preference'] ?: 'Any'); ?></small>
                                                    </td>
                                                    <td>
                                                        <span class="badge" style="<?php echo $status_style; ?>">
                                                            <?php echo ucfirst(htmlspecialchars($task['status'])); ?>
                                                        </span>
                                                        <?php if ($task['collection_time']): ?><small class="d-block text-muted">Collected: <?php echo date("d/m H:i", strtotime($task['collection_time'])); ?></small><?php endif; ?>
                                                        <?php if ($task['delivery_time']): ?><small class="d-block text-muted">Delivered: <?php echo date("d/m H:i", strtotime($task['delivery_time'])); ?></small><?php endif; ?>
                                                    </td>
                                                    <td class="text-center action-buttons">
                                                        <?php if ($task['status'] === 'assigned'): ?>
                                                            <form action="volunteer_dashboard.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="donation_id" value="<?php echo $task['donation_id']; ?>">
                                                                <button type="submit" name="mark_collected" class="btn btn-primary btn-sm" title="Mark as Collected">
                                                                    <i class="bi bi-box-arrow-down me-1"></i> Collected
                                                                </button>
                                                            </form>
                                                        <?php elseif ($task['status'] === 'collected'): ?>
                                                            <form action="volunteer_dashboard.php" method="POST" style="display: inline;">
                                                                <input type="hidden" name="donation_id" value="<?php echo $task['donation_id']; ?>">
                                                                <button type="submit" name="mark_delivered" class="btn btn-warning btn-sm text-dark" title="Mark as Delivered">
                                                                    <i class="bi bi-truck me-1"></i> Delivered
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                        <a href="#" class="btn btn-secondary btn-sm" title="View Full Details"><i class="bi bi-info-circle"></i></a>
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

<!-- Add Edit Profile Modal at the end of body -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" action="">
        <div class="modal-header" style="background:#ff8c00;color:#fff;">
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
          <button type="submit" name="edit_profile_submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Sidebar active state logic for volunteer
(function() {
    function clearSidebarActive() {
        document.querySelectorAll('.volunteer-sidebar .nav-link').forEach(function(link) {
            link.classList.remove('active');
            link.style.background = '';
            link.style.color = '';
        });
    }
    function setSidebarActive(id) {
        var link = document.getElementById(id);
        if (link) {
            link.classList.add('active');
            link.style.background = '#ff8c00';
            link.style.color = '#fff';
        }
    }
    function updateSidebarActive() {
        clearSidebarActive();
        var hash = window.location.hash;
        if (window.location.pathname.endsWith('volunteer_dashboard.php') && (!hash || hash === '#')) {
            setSidebarActive('sidebar-dashboard');
        } else if (hash === '#available-tasks') {
            setSidebarActive('sidebar-available-tasks');
        } else if (hash === '#my-tasks') {
            setSidebarActive('sidebar-my-tasks');
        } else if (window.location.pathname.includes('edit_volunteer_profile.php')) {
            setSidebarActive('sidebar-edit-profile');
        } else {
            setSidebarActive('sidebar-dashboard');
        }
    }
    window.addEventListener('DOMContentLoaded', updateSidebarActive);
    window.addEventListener('hashchange', updateSidebarActive);
})();
</script>

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

