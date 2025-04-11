<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap & Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./header.css">
    <title>Responsive Navbar</title>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-light fixed-top shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand" onclick="window.location.href='index.php'" href="#">
      <img src="./images/food.png" alt="Logo">
    </a>

    <!-- Toggler -->
    <button class="navbar-toggler" type="button" id="navbarToggler" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Content -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <!-- <li class="nav-item">
          <a class="nav-link" onclick="window.location.href='Fundraising.php'" href="#">Fundraising goals</a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link" onclick="window.location.href='about.php'" href="#">About us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" onclick="window.location.href='FAQs.php'" href="#">FAQs</a>
        </li>
        <!-- Hidden initially -->
        <li class="nav-item d-none" id="donationStatusItem">
          <a class="nav-link" id="donationStatusLink" href="#">Donation Status</a>
        </li>
      </ul>

      <!-- Right Side: Buttons -->
      <div class="d-flex align-items-center">
        <span id="userGreeting" class="me-3 fw-semibold text-primary d-none"></span>
        <button id="signBtn" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Sign in</button>
        <button id="donateBtn" type="button" class="btn btn-warning me-2">Donate</button>
        <button id="logoutBtn" class="btn btn-danger d-none">Logout</button>
      </div>
    </div>
  </div>
</nav>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Logic Script -->
<script>
window.onload = function () {
  const user = localStorage.getItem("user_name");
  const loginMsg = localStorage.getItem("loginMsg");
  const user_id = localStorage.getItem("user_id");
  const user_type = localStorage.getItem("user_type");

  const signBtn = document.getElementById("signBtn");
  const logoutBtn = document.getElementById("logoutBtn");
  const userGreeting = document.getElementById("userGreeting");
  const donateBtn = document.getElementById("donateBtn");
  const donationStatusItem = document.getElementById("donationStatusItem");
  const donationStatusLink = document.getElementById("donationStatusLink");

  if (user) {
    signBtn.classList.add("d-none");
    logoutBtn.classList.remove("d-none");

    userGreeting.textContent = `Welcome, ${user}!`;
    userGreeting.classList.remove("d-none");

    // Show donation status
    donationStatusItem.classList.remove("d-none");

    // Update donation status link with user ID
    if (user_id) {
      donationStatusLink.href = `view_donation_status.php?user_id=${user_id}`;
    }

    // Go to donation page
    donateBtn.onclick = function () {
      window.location.href = 'donate.php';
    };
  } else {
    // Prompt login
    donateBtn.onclick = function () {
      alert("Please log in to donate.");
      window.location.href='index.php'
      const signInModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
      signInModal.show();
    };
  }

  // Logout
  logoutBtn.onclick = function () {
    localStorage.clear();
    alert("Logged out successfully!");
    location.reload();
  };
};
</script>

</body>
</html>
