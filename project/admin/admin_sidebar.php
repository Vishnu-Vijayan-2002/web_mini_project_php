<!-- admin/admin_sidebar.php -->
<style>
    .sidebar {
        height: 100vh;
        position: fixed;
        width: 240px;
        background-color: #ffc107;
        color: black;
        padding-top: 20px;
        font-weight: 600;
        font-size: 18px;
    }
    .sidebar a {
        color: black;
        display: block;
        padding: 12px 20px;
        text-decoration: none;
    }
    .sidebar a:hover {
        background-color: #ffc107;
    }
</style>

<div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <hr class="bg-white">
    <a href="index.php">🏠 Dashboard</a>
    <a href="manage_users.php">👥 Manage Users</a>
    <a href="manage_cash_donations.php">💰 Cash Donations</a>
    <a href="manage_donations.php">🍱 Food Donations</a>
    <a href="volunteer__notification_requests.php">📢 Send Notification</a>
    <a href="volunteer_requests.php">🧑‍🤝‍🧑 Volunteer Requests</a>
    <a href="settings.php">⚙️ Settings</a>
    <a href="#" onclick="logout()">🚪 Logout</a>
</div>

<script>
function logout() {
    localStorage.clear(); // Remove all localStorage values
    alert("Logged out successfully.");
    window.location.href = "../index.php"; // Redirect to homepage or login page
}
</script>
