<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="donate.css">
  <link rel="icon" sizes="32x32" href="./images/logo.png" type="image/png">
  <link rel="apple-touch-icon" href="./images/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Feed people in need</title>
</head>
<body>
<?php include 'header.php' ?>
       <!-- sign up -->
       <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
       <?php include 'signup.php' ?>
    </div>
  </div>
</div>
<div class="main">
<div class="hero-section">
    <div class="hero-image-wrapper">
        <img  src="https://images.ctfassets.net/z0x29akdg5eb/6VnhBZx3jaRKjcBBdAZDGb/349b6786025b078c9e719191a4abdf8f/Cover_Photo_November_12.JPG?w=741&h=494&fit=fill&q=80&fm=avif" 
        alt="Mother and child" class="hero-banner">
    </div>

    <div class="hero-content">
    <p class="hero-subheading">We believe in a world without hunger.</p>
    <h1>Together, we can create a future<br>where no one goes to bed hungry.</h1>

    <p class="hero-description">
        Millions of people around the world struggle with hunger every day — but it doesn’t have to be this way.  
        Hunger is a challenge we can overcome through compassion, action, and collective support.  
        When we come together and share what we can, even a single meal can bring hope, strength, and dignity.
        <strong>Every act of kindness counts. Every meal matters.</strong>
    </p>
</div>

</div>
   
    <button onclick="window.location.href='donate_meals.php'" class="meals">Donate Meals</button>
</div>
<!-- Scroll to Top Button -->
<?php  include 'scrolling.php' ?>
<?php include 'footer.php' ?>
<script src="./js/donate.js"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
  // Donation Amount Button Toggle Logic
  function updateCard(amount, impact) {
    document.getElementById("donationAmount").textContent = amount;
    document.getElementById("impactText").innerHTML = impact;

    // Remove selected class from all and add to clicked
    const buttons = document.querySelectorAll(".donation-option");
    buttons.forEach(btn => btn.classList.remove("selected"));
    event.target.classList.add("selected");

    // Clear custom input if predefined amount clicked
    document.getElementById("customAmount").value = '';
  }

  // For custom input
  function updateCustomCard() {
    const amount = document.getElementById("customAmount").value;
    if (amount > 0) {
      document.getElementById("donationAmount").textContent = amount;
      document.getElementById("impactText").innerHTML = `This can provide food to <strong>${Math.floor(amount / 30)}</strong> people in need`;
      
      // Remove selected class from all buttons
      const buttons = document.querySelectorAll(".donation-option");
      buttons.forEach(btn => btn.classList.remove("selected"));
    }
  }

  window.onload = function () {
    const user = localStorage.getItem("user_name");

    const donateMealsBtn = document.querySelector(".meals");

    donateMealsBtn.onclick = function (e) {
      e.preventDefault();

      if (user) {
        // User is logged in, proceed to donation page
        window.location.href = 'donate_meals.php';
      } else {
        // Not logged in
        alert("Please log in to donate meals.");
        const signInModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
        signInModal.show();
      }
    };
  };
  function validateAndContinue() {
  const email = document.getElementById("emailInput").value.trim();
  const amount = parseInt(document.getElementById("donationAmount").textContent);
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!email || !emailRegex.test(email)) {
    alert("Please enter a valid email.");
    return;
  }

  if (!amount || isNaN(amount) || amount <= 0) {
    alert("Please select a valid donation amount.");
    return;
  }

  alert(`Thank you for donating ₹${amount} with email: ${email}`);
  // You can add further logic to submit this info via AJAX or a form
}
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

</html>
