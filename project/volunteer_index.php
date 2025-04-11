<?php
session_start();

// Only handle AJAX requests to this same file for JSON data
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    
    include './db/db.php';
    
    // Check if this is a status update request
    if (isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
        if (isset($_POST['donationId']) && isset($_POST['volunteer_status'])) {
            $donationId = $_POST['donationId'];
            $newStatus = $_POST['volunteer_status'];
            
            // Use prepared statements for security
            $stmt = $conn->prepare("UPDATE donations SET volunteer_status = ? WHERE id = ?");
            $stmt->bind_param("ss", $newStatus, $donationId);
            $updateResult = $stmt->execute();
            
            echo json_encode(['success' => $updateResult ? true : false]);
            $stmt->close();
            $conn->close();
            exit;
        }
    }
    
    // Regular data fetch request
    $sql = "SELECT * FROM donations";
    $result = $conn->query($sql);
    
    $donations = [];
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $donations[] = $row;
        }
    }
    
    $conn->close();
    echo json_encode($donations);
    exit; // Stop execution after serving JSON
}

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: volunteer_login.php");
    exit;
}

$name = $_SESSION['user_name'];
$email = $_SESSION['user_email'];

// Get user profile data for the profile section
$user = []; // This would normally be fetched from the database

// For demonstration, prefill with session data
$user['full_name'] = $name;
$user['email'] = $email;
$user['phone'] = $_SESSION['user_phone'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Food Donation Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="volunter_index.css">
    <style>
        /* Additional styles for status buttons in view modal */
        .status-buttons {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .status-btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .status-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .pickup-status-btn {
            background-color: #f0ad4e;
            color: white;
        }
        
        .complete-status-btn {
            background-color: #5cb85c;
            color: white;
        }
        
        .status-note {
            font-size: 12px;
            font-style: italic;
            margin-top: 5px;
            color: #666;
        }
        
        /* Status colors */
        .status-available {
            background-color: #5bc0de;
        }
        
        .status-processing, .status-pickup {
            background-color: #f0ad4e;
        }
        
        .status-completed {
            background-color: #5cb85c;
        }
    </style>
</head>
<body>
    <div class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </div>
    
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="profile">
                <img src="https://static.vecteezy.com/system/resources/previews/000/439/863/non_2x/vector-users-icon.jpg" alt="Profile" class="profile-img">
                <h3><?php echo htmlspecialchars($name); ?></h3>
                <h3><?php echo htmlspecialchars($email); ?></h3>
            </div>
            
            <div class="nav-menu">
                <div class="nav-item active" data-section="donations">
                    <i class="fas fa-utensils"></i> Food Donations
                </div>
                <div class="nav-item" data-section="profile">
                    <i class="fas fa-user"></i> Profile Settings
                </div>
                
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1 id="section-title">Food Donations</h1>
                <button class="logout-btn" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
            
            <!-- Donations Section -->
            <div class="dashboard-section active" id="donations-section">
                <div class="stats-container">
                    <div class="stat-card">
                        <h3>Available Donations</h3>
                        <p id="available-count">0</p>
                    </div>
                    <div class="stat-card">
                        <h3>Processing Pickups</h3>
                        <p id="processing-count">0</p>
                    </div>
                    <div class="stat-card">
                        <h3>Completed Today</h3>
                        <p id="completed-count">0</p>
                    </div>
                </div>
                
                <table class="donations-table">
                    <thead>
                        <tr>
                            <th>Donation ID</th>
                            <th>Food Type</th>
                            <th>Quantity</th>
                            <th>Location</th>
                            <th>Donor</th>
                            <th>Donor Status</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="donation-table-body">
                        <!-- Data will be injected here -->
                    </tbody>
                </table>
            </div>
            
            <!-- Profile Section -->
            <div class="dashboard-section" id="profile-section">
                <h2>Profile Settings</h2>
                <div class="stat-card" style="margin: 20px 0; text-align: left;">
                    <form id="profile-form" method="POST" action="update_profile.php">
                        <div class="form-group">
                            <label for="profile-name">Full Name</label>
                            <input type="text" id="profile-name" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="profile-email">Email</label>
                            <input type="email" id="profile-email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="profile-phone">Phone</label>
                            <input type="tel" id="profile-phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Update Profile</button>
                    </form>
                </div>
            </div>
            
            <!-- Schedule Section -->
            <div class="dashboard-section" id="schedule-section">
                <h2>My Schedule</h2>
                <div class="stat-card" style="margin: 20px 0;">
                    <h3>Upcoming Pickups</h3>
                    <ul style="list-style-type: none; padding: 0;">
                        <li style="padding: 10px; border-bottom: 1px solid #eee;">
                            <strong>#FD-1003</strong> - May 22, 2:00 PM (Canned Goods)
                        </li>
                        <li style="padding: 10px; border-bottom: 1px solid #eee;">
                            <strong>#FD-1012</strong> - May 23, 10:00 AM (Bakery Items)
                        </li>
                        <li style="padding: 10px;">
                            <strong>#FD-1015</strong> - May 24, 3:30 PM (Fresh Produce)
                        </li>
                    </ul>
                </div>
                <button class="btn btn-primary">View Full Calendar</button>
            </div>
        </div>
    </div>
    
    <!-- Modal for Pickup Confirmation -->
    <div class="modal" id="pickupModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Pickup</h2>
                <button class="close-btn" onclick="closeModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="donationId">Donation ID</label>
                    <input type="text" id="donationId" readonly>
                </div>
                <div class="form-group">
                    <label for="foodType">Food Type</label>
                    <input type="text" id="foodType" readonly>
                </div>
                <div class="form-group">
                    <label for="donorStatus">Donor Status</label>
                    <input type="text" id="donorStatus" readonly>
                </div>
                <div class="form-group">
                    <label for="notes">Notes (Optional)</label>
                    <textarea id="notes" placeholder="Any special instructions..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button class="btn btn-primary" onclick="confirmPickup()">Confirm Pickup</button>
            </div>
        </div>
    </div>
    
    <!-- Modal for Viewing Donation Details -->
    <div class="modal" id="viewModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Donation Details</h2>
                <button class="close-btn" onclick="closeViewModal()">×</button>
            </div>
            <div class="modal-body">
                <table>
                    <tr><th>Donation ID:</th><td id="view-donationId"></td></tr>
                    <tr><th>Food Type:</th><td id="view-foodType"></td></tr>
                    <tr><th>Quantity:</th><td id="view-quantity"></td></tr>
                    <tr><th>Location:</th><td id="view-location"></td></tr>
                    <tr><th>Donor:</th><td id="view-donor"></td></tr>
                    <tr><th>Donor Status:</th><td id="view-donorStatus"></td></tr>
                    <tr><th>Status:</th><td id="view-status"></td></tr>
                </table>
                
                <!-- Status Update Buttons -->
                <div class="status-buttons">
                    <button id="pickup-status-btn" class="status-btn pickup-status-btn" onclick="updateDonationStatus('Pickup')">Mark as Pickup</button>
                    <button id="complete-status-btn" class="status-btn complete-status-btn" onclick="updateDonationStatus('Completed')">Mark as Completed</button>
                </div>
                <p class="status-note">Note: Status changes are tracked and recorded in the system.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeViewModal()">Close</button>
            </div>
        </div>
    </div>
    
    <script>
        // Track processing donations and current viewed donation
        let processingDonations = [];
        let currentDonationId = null;
        let viewedDonationId = null;
        
        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (!sidebar.contains(event.target) && event.target !== menuToggle && !menuToggle.contains(event.target)) {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('active');
                }
            }
        });
        
        // Sidebar Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            const sections = document.querySelectorAll('.dashboard-section');
            const sectionTitle = document.getElementById('section-title');
            
            // Set up click handlers for each nav item
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Remove active class from all items
                    navItems.forEach(nav => nav.classList.remove('active'));
                    // Add active class to clicked item
                    this.classList.add('active');
                    
                    // Get the section to show
                    const sectionId = this.getAttribute('data-section');
                    
                    // Hide all sections
                    sections.forEach(section => section.classList.remove('active'));
                    
                    // Show the selected section
                    document.getElementById(`${sectionId}-section`).classList.add('active');
                    
                    // Update the section title based on the nav item text
                    if (sectionId === 'donations') {
                        sectionTitle.textContent = 'Food Donations';
                    } else if (sectionId === 'profile') {
                        sectionTitle.textContent = 'Profile Settings';
                    } else if (sectionId === 'schedule') {
                        sectionTitle.textContent = 'Schedule';
                    }
                    
                    // Close sidebar on mobile after selection
                    if (window.innerWidth <= 768) {
                        document.getElementById('sidebar').classList.remove('active');
                    }
                });
            });
            
            // Initial data load
            fetchDonations();
        });
        
        // Fetch donations data
        function fetchDonations() {
            // Use the current URL with an AJAX header
            fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                updateDonationTable(data);
            })
            .catch(error => {
                console.error('Error fetching donations:', error);
            });
        }
        
        // Update donation table with fetched data
        function updateDonationTable(donations) {
            const tbody = document.getElementById('donation-table-body');
            tbody.innerHTML = ''; // Clear previous data
            
            let availableCount = 0;
            let completedToday = 0;
            processingDonations = []; // Reset processing donations
            
            donations.forEach(donation => {
                const row = document.createElement('tr');
                row.setAttribute('data-id', donation.id);
                
                // Get the volunteer status field value (may be named different in DB)
                const status = donation.volunteer_status || donation.status || 'Available';
                
                // Determine status span class
                let statusSpan = '';
                if (status === 'Available') {
                    availableCount++;
                    statusSpan = `<span class="status status-available">${status}</span>`;
                } else if (status === 'Pickup Processing') {
                    processingDonations.push(donation.id);
                    statusSpan = `<span class="status status-processing">${status}</span>`;
                } else if (status === 'Pickup') {
                    processingDonations.push(donation.id);
                    statusSpan = `<span class="status status-pickup">${status}</span>`;
                } else if (status === 'Completed') {
                    completedToday++;
                    statusSpan = `<span class="status status-completed">${status}</span>`;
                } else {
                    statusSpan = `<span class="status">${status}</span>`;
                }
                
                // Create action buttons based on status
                let actionBtns = '';
                if (status === 'Available') {
                    actionBtns = `
                        <button class="action-btn pickup-btn" onclick="openModal('${donation.id}')">Pick Up</button>
                        <button class="action-btn view-btn" onclick="viewDetails('${donation.id}')">View</button>
                    `;
                } else if (status === 'Pickup Processing' || status === 'Pickup') {
                    actionBtns = `
                        <button class="action-btn pickup-btn" disabled>${status}</button>
                        <button class="action-btn cancel-btn" onclick="cancelPickup('${donation.id}')">Cancel</button>
                        <button class="action-btn view-btn" onclick="viewDetails('${donation.id}')">View</button>
                    `;
                } else {
                    actionBtns = `<button class="action-btn view-btn" onclick="viewDetails('${donation.id}')">View</button>`;
                }
                
                // Create table row with correct database field mappings
                row.innerHTML = `
                    <td>${donation.id}</td>
                    <td>${donation.meal_type || donation.food_type}</td>
                    <td>${donation.food_quantity || donation.quantity}</td>
                    <td>${donation.address || donation.location}</td>
                    <td>${donation.first_name || donation.donor}</td>
                    <td>${donation.donation_status || donation.donor_status}</td>
                    <td>${statusSpan}</td>
                    <td>${actionBtns}</td>
                `;
                
                tbody.appendChild(row);
            });
            
            // Update stats
            document.getElementById('available-count').textContent = availableCount;
            document.getElementById('processing-count').textContent = processingDonations.length;
            document.getElementById('completed-count').textContent = completedToday;
        }
        
        // Modal functions
        function openModal(donationId) {
            const modal = document.getElementById('pickupModal');
            document.getElementById('donationId').value = donationId;
            currentDonationId = donationId;
            
            // Find donation details
            const row = document.querySelector(`tr[data-id="${donationId}"]`);
            if (row) {
                const cells = row.querySelectorAll('td');
                document.getElementById('foodType').value = cells[1].textContent;
                document.getElementById('donorStatus').value = cells[5].textContent;
            }
            
            modal.style.display = 'flex';
        }
        
        function closeModal() {
            document.getElementById('pickupModal').style.display = 'none';
            currentDonationId = null;
        }
        
        function confirmPickup() {
            const donationId = document.getElementById('donationId').value;
            const notes = document.getElementById('notes').value;
            
            // Update status to pickup processing
            updateDonationStatus('Pickup Processing', donationId);
            closeModal();
        }
        
        function cancelPickup(donationId) {
            // Make API call to update status back to Available
            updateDonationStatus('Available', donationId);
        }
        
        // View donation details
        function viewDetails(donationId) {
            // Find the row
            const row = document.querySelector(`tr[data-id="${donationId}"]`);
            if (!row) return;
            
            // Get all cells
            const cells = row.querySelectorAll("td");
            viewedDonationId = donationId;
            
            // Populate modal
            document.getElementById("view-donationId").textContent = cells[0].textContent;
            document.getElementById("view-foodType").textContent = cells[1].textContent;
            document.getElementById("view-quantity").textContent = cells[2].textContent;
            document.getElementById("view-location").textContent = cells[3].textContent;
            document.getElementById("view-donor").textContent = cells[4].textContent;
            document.getElementById("view-donorStatus").textContent = cells[5].textContent;
            
            const currentStatus = cells[6].querySelector(".status").textContent;
            document.getElementById("view-status").textContent = currentStatus;
            
            // Enable/disable status buttons based on current status
            const pickupBtn = document.getElementById('pickup-status-btn');
            const completeBtn = document.getElementById('complete-status-btn');
            
            if (currentStatus === 'Available') {
                pickupBtn.disabled = false;
                completeBtn.disabled = true;
            } else if (currentStatus === 'Pickup Processing' || currentStatus === 'Pickup') {
                pickupBtn.disabled = true;
                completeBtn.disabled = false;
            } else if (currentStatus === 'Completed') {
                pickupBtn.disabled = true;
                completeBtn.disabled = true;
            } else {
                pickupBtn.disabled = false;
                completeBtn.disabled = false;
            }
            
            // Show modal
            document.getElementById("viewModal").style.display = "block";
        }
        
        // Update donation status via AJAX request with optional donationId parameter
        function updateDonationStatus(newStatus, donationId = null) {
            // If donationId is not provided, use the currently viewed donation
            const idToUpdate = donationId || viewedDonationId;
            
            if (!idToUpdate) return;
            
            // Send status update to the server
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `action=updateStatus&donationId=${idToUpdate}&volunteer_status=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI if server request was successful
                    updateDonationStatusInUI(idToUpdate, newStatus);
                    
                    // If we're in the view modal, update its status too
                    if (viewedDonationId === idToUpdate) {
                        document.getElementById("view-status").textContent = newStatus;
                        
                        // Disable appropriate buttons based on new status
                        const pickupBtn = document.getElementById('pickup-status-btn');
                        const completeBtn = document.getElementById('complete-status-btn');
                        
                        if (newStatus === 'Pickup') {
                            pickupBtn.disabled = true;
                            completeBtn.disabled = false;
                        } else if (newStatus === 'Completed') {
                            pickupBtn.disabled = true;
                            completeBtn.disabled = true;
                        }
                    }
                } else {
                    alert('Failed to update status. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                
                // For demo purposes, still update the UI
                updateDonationStatusInUI(idToUpdate, newStatus);
                
                if (viewedDonationId === idToUpdate) {
                    document.getElementById("view-status").textContent = newStatus;
                }
            });
        }
        
        // Update the donation status in the UI
        function updateDonationStatusInUI(donationId, newStatus) {
            const row = document.querySelector(`tr[data-id="${donationId}"]`);
            if (!row) return;
            
            // Update status cell
            const statusCell = row.querySelector('td:nth-child(7)');
            let statusClass = '';
            
            switch(newStatus) {
                case 'Available':
                    statusClass = 'status-available';
                    break;
                case 'Pickup Processing':
                case 'Pickup':
                    statusClass = 'status-pickup';
                    break;
                case 'Completed':
                    statusClass = 'status-completed';
                    break;
                default:
                    statusClass = '';
            }
            
            statusCell.innerHTML = `<span class="status ${statusClass}">${newStatus}</span>`;
            
            // Update action buttons based on new status
            const actionCell = row.querySelector('td:nth-child(8)');
            
            if (newStatus === 'Available') {
                actionCell.innerHTML = `
                    <button class="action-btn pickup-btn" onclick="openModal('${donationId}')">Pick Up</button>
                    <button class="action-btn view-btn" onclick="viewDetails('${donationId}')">View</button>
                `;
                
                // Remove from processing if it was there
                processingDonations = processingDonations.filter(id => id !== donationId);
            } 
            else if (newStatus === 'Pickup Processing' || newStatus === 'Pickup') {
                actionCell.innerHTML = `
                    <button class="action-btn pickup-btn" disabled>${newStatus}</button>
                    <button class="action-btn cancel-btn" onclick="cancelPickup('${donationId}')">Cancel</button>
                    <button class="action-btn view-btn" onclick="viewDetails('${donationId}')">View</button>
                `;
                
                // Add to processing if not already there
                if (!processingDonations.includes(donationId)) {
                    processingDonations.push(donationId);
                }
            } 
            else if (newStatus === 'Completed') {
                actionCell.innerHTML = `
                    <button class="action-btn view-btn" onclick="viewDetails('${donationId}')">View</button>
                `;
                
                // Remove from processing if it was there
                processingDonations = processingDonations.filter(id => id !== donationId);
            }
            
            // Update statistics
            updateStats();
        }
        
        // Update the stats counters
        function updateStats() {
            // Update counts in the stats cards
            document.getElementById('available-count').textContent = 
                document.querySelectorAll('.status-available').length;
                
            // Count both processing and pickup statuses
            const processingCount = document.querySelectorAll('.status-processing').length + 
                                   document.querySelectorAll('.status-pickup').length;
            document.getElementById('processing-count').textContent = processingCount;
            
            // Update completed count
            document.getElementById('completed-count').textContent = 
                document.querySelectorAll('.status-completed').length;
        }
        
        function closeViewModal() {
            document.getElementById("viewModal").style.display = "none";
            viewedDonationId = null;
        }
        
        function logout() {
            window.location.href = "index.php?logout=true";
        }
        
        // Close modals when clicking outside
        window.onclick = function(event) {
            const pickupModal = document.getElementById('pickupModal');
            const viewModal = document.getElementById('viewModal');
            
            if (event.target == pickupModal) {
                closeModal();
            }
            
            if (event.target == viewModal) {
                closeViewModal();
            }
        };
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('sidebar').classList.remove('active');
            }
        });
    </script>
</body>
</html>