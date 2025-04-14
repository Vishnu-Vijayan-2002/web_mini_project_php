<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="donate.css">
  <link rel="icon" sizes="32x32" href="./images/logo.png" type="image/png">
  <link rel="apple-touch-icon" href="./images/logo.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Feed people in need</title>
</head>
<body>

<?php include 'header.php' ?>

<!-- Sign-up Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php include 'signup.php' ?>
    </div>
  </div>
</div>

<!-- Hero & Donation Section Container -->
<div class="hero-donation-container">
  <!-- Unified Donation Card -->
  <div class="donation-card">
    <div class="donation-content">
      <h2>Support Those in Need</h2>
      <p>Choose how you'd like to help—whether it's food, cash, or requesting aid.</p>
    </div>

    <div class="donation-buttons">
      <button class="btn btn-success" id="donate-meals-btn">Donate Meals</button>
      <button class="btn btn-warning" id="donate-cash-btn">Donate Cash</button>
      <button class="btn btn-danger" id="request-donation-btn">Request Donation</button>
    </div>
  </div>

  <!-- Hero Section -->
  <div class="hero-section">
    <div class="hero-image-wrapper">
      <img src="https://images.ctfassets.net/z0x29akdg5eb/6VnhBZx3jaRKjcBBdAZDGb/349b6786025b078c9e719191a4abdf8f/Cover_Photo_November_12.JPG?w=741&h=494&fit=fill&q=80&fm=avif"
        alt="Mother and child" class="hero-banner">
    </div>
    <div class="hero-content">
      <p class="hero-subheading">We believe in a world without hunger.</p>
      <h1>Together, we can create a future where no one goes to bed hungry.</h1>
      <p class="hero-description">
        Millions of people around the world struggle with hunger every day — but it doesn’t have to be this way.
        Hunger is a challenge we can overcome through compassion, action, and collective support.
        <strong>Every act of kindness counts. Every meal matters.</strong>
      </p>
    </div>
  </div>
</div>

<!-- Quotes Section -->
<div class="quotes-section">
  <h2>Inspiration to Give</h2>
  <p>Helping those in need is a reflection of our shared humanity. Let these words inspire you to make a difference.</p>
  
  <div class="quote">
    <p>🌱 "If you can't feed a hundred people, then feed just one." – Mother Teresa</p>
  </div>
  <div class="quote">
    <p>💛 "The best way to find yourself is to lose yourself in the service of others." – Mahatma Gandhi</p>
  </div>
  <div class="quote">
    <p>🍞 "Hunger is not an issue of charity. It is an issue of justice." – Jacques Diouf</p>
  </div>
  <div class="quote">
    <p>🤝 "We make a living by what we get, but we make a life by what we give." – Winston Churchill</p>
  </div>
</div>

<!-- Scroll to Top & Footer -->
<?php include 'scrolling.php' ?>
<?php include 'footer.php' ?>

<!-- Scripts -->
<script src="./js/donate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  window.onload = function () {
    const user = localStorage.getItem("user_name");

    const donateMealsBtn = document.getElementById("donate-meals-btn");
    const donateCashBtn = document.getElementById("donate-cash-btn");
    const requestDonationBtn = document.getElementById("request-donation-btn");

    donateMealsBtn.onclick = function (e) {
      e.preventDefault();
      if (user) {
        window.location.href = 'donate_meals.php';
      } else {
        alert("Please log in to donate meals.");
        const signInModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
        signInModal.show();
      }
    };

    donateCashBtn.onclick = function () {
      window.location.href = 'donate_cash.php';
    };

    requestDonationBtn.onclick = function () {
      window.location.href = 'donation_request.php';
    };
  };
</script>
</body>
</html>
