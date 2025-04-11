<?php
include '../db/db.php';

// Total Users (excluding admin)
$userCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE user_type != 'admin'")->fetch_assoc()['total'];

// Total Donations
$donationCount = $conn->query("SELECT COUNT(*) AS total FROM donations")->fetch_assoc()['total'];

// Pending Requests
$pendingRequests = $conn->query("SELECT COUNT(*) AS total FROM volunteers WHERE status = 'pending'")->fetch_assoc()['total'];

// User Type Distribution
$adminCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE user_type = 'admin'")->fetch_assoc()['total'];
$volunteerCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE user_type = 'volunteer'")->fetch_assoc()['total'];
$citizenCount = $conn->query("SELECT COUNT(*) AS total FROM users WHERE user_type = 'citizen'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { overflow-x: hidden; font-family: 'Segoe UI', sans-serif; background-color: #f8f9fa; }
        .main-content { margin-left: 240px; padding: 30px; }
        .card:hover { transform: scale(1.01); transition: 0.3s ease; }
        .card-icon { font-size: 1.5rem; margin-bottom: 10px; color: #0d6efd; }
        .chart-container { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

<?php include 'admin_sidebar.php'; ?>

<div class="main-content">
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h4">Welcome, <strong id="adminName"></strong></span>
        </div>
    </nav>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <div class="card-icon">👥</div>
                <h6 class="text-muted">Total Users</h6>
                <h4><?= $userCount ?></h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <div class="card-icon">🍱</div>
                <h6 class="text-muted">Total Donations</h6>
                <h4><?= $donationCount ?></h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center p-4 shadow-sm">
                <div class="card-icon">🕒</div>
                <h6 class="text-muted">Pending Requests</h6>
                <h4><?= $pendingRequests ?></h4>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="chart-container">
                <canvas id="barChart"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const userType = localStorage.getItem('user_type');
    const adminName = localStorage.getItem('user_name');
    if (userType !== 'admin') {
        alert('Unauthorized access!');
        window.location.href = '../index.php';
    }
    document.getElementById('adminName').innerText = adminName;

    function logout() {
        localStorage.clear();
        window.location.href = '../index.php';
    }

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: ['Users', 'Donations', 'Pending'],
            datasets: [{
                label: 'System Stats',
                data: [<?= $userCount ?>, <?= $donationCount ?>, <?= $pendingRequests ?>],
                backgroundColor: ['#0d6efd', '#198754', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'Overview', font: { size: 18 } },
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Pie Chart
    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Admins', 'Volunteers', 'Citizens'],
            datasets: [{
                data: [<?= $adminCount ?>, <?= $volunteerCount ?>, <?= $citizenCount ?>],
                backgroundColor: ['#dc3545', '#0d6efd', '#20c997']
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: { display: true, text: 'User Role Distribution', font: { size: 18 } }
            }
        }
    });
</script>

</body>
</html>
