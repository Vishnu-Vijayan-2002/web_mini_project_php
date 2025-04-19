<?php
require_once 'admin_auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - FoodShare Connect</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --navbar-height: 56px;
            --sidebar-width: 280px;
        }

        body {
            padding-top: var(--navbar-height);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .sidebar-nav .nav-link {
            transition: background-color 0.2s ease, color 0.2s ease;
            color: rgba(255, 255, 255, 0.65);
            padding: 0.75rem 1.25rem;
            font-size: 0.95rem;
        }
        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
        }
        .sidebar-nav .nav-link.active {
            background-color: rgba(0, 0, 0, 0.2);
            color: #fff;
            font-weight: 500;
        }
        .sidebar-nav .nav-link i {
            width: 1.5em;
        }

        main {
            flex-grow: 1;
            padding: 1.5rem;
            transition: margin-left 0.3s ease-in-out;
        }

        @media (min-width: 768px) {
            .offcanvas-fixed.offcanvas-start {
                transform: none;
                visibility: visible !important;
                top: var(--navbar-height);
                height: calc(100vh - var(--navbar-height));
                width: var(--sidebar-width);
                position: fixed;
                border-right: 1px solid rgba(0, 0, 0, 0.1);
                overflow-y: auto;
            }

            main {
                margin-left: var(--sidebar-width);
                padding: 1.5rem 2rem;
            }
        }

        .action-buttons form { display: inline; }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
        <nav class="navbar navbar-dark bg-dark fixed-top" style="height: var(--navbar-height);">
      <div class="container-fluid">
        <!-- Sidebar Toggle Button - Hidden on md screens and up -->
        <button class="navbar-toggler me-2 border-0 d-md-none" 
                type="button" data-bs-toggle="offcanvas" data-bs-target="#adminSidebar"
                aria-controls="adminSidebar" aria-label="Toggle sidebar">
           <i class="bi bi-list fs-4 text-white"></i>
        </button>

        <!-- Navbar Brand -->
        <a class="navbar-brand me-auto" href="dashboard.php">FoodShare Admin</a>

        <!-- User Dropdown -->
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2 fs-4"></i>
            <strong>
                <?php
                echo isset($_SESSION['first_name']) && $_SESSION['first_name']
                    ? htmlspecialchars($_SESSION['first_name'])
                    : 'Admin';
                ?>
            </strong>
          </a>
          <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="../logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Sidebar using Offcanvas -->
    <div class="offcanvas offcanvas-start offcanvas-fixed bg-dark text-white sidebar-nav" tabindex="-1" id="adminSidebar" aria-labelledby="adminSidebarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="adminSidebarLabel">Menu</h5>
            <button type="button" class="btn-close btn-close-white text-reset d-md-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pt-0">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_volunteers.php') ? 'active' : ''; ?>" href="manage_volunteers.php">
                        <i class="bi bi-person-check me-2"></i> Volunteers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_donations.php') ? 'active' : ''; ?>" href="manage_donations.php">
                        <i class="bi bi-box-seam me-2"></i> Donations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_users.php') ? 'active' : ''; ?>" href="manage_users.php">
                        <i class="bi bi-people me-2"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'send_email.php') ? 'active' : ''; ?>" href="send_email.php">
                        <i class="bi bi-envelope-fill me-2"></i> Send Email
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'password_resets.php') ? 'active' : ''; ?>" href="password_resets.php">
                        <i class="bi bi-key-fill me-2"></i> Password Resets
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_cash_donations.php') ? 'active' : ''; ?>" href="manage_cash_donations.php">
                        <i class="bi bi-currency-rupee me-2"></i> Cash Donations
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'manage_help_requests.php') ? 'active' : ''; ?>" href="manage_help_requests.php">
                        <i class="bi bi-clipboard-heart me-2"></i> Help Requests
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="px-md-4 py-3">
        <nav aria-label="breadcrumb" class="mb-3 d-none d-md-block">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                    <?php
                    $page_title = ucfirst(str_replace(['manage_', '.php'], ['', ''], basename($_SERVER['PHP_SELF'])));
                    echo htmlspecialchars($page_title);
                    ?>
                </li>
            </ol>
        </nav>
        <!-- Page Content Start -->
        <!-- The specific page content (like tables) will go here -->