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
        <img src="https://images.ctfassets.net/z0x29akdg5eb/6VnhBZx3jaRKjcBBdAZDGb/349b6786025b078c9e719191a4abdf8f/Cover_Photo_November_12.JPG?w=741&h=494&fit=fill&q=80&fm=avif" 
        alt="Mother and child" class="hero-banner">
    </div>

    <div class="hero-content">
        <p class="hero-subheading">There's one thing we'll never stop believing in</p>
        <h1>Together, we can be the generation<br>that ends global hunger.</h1>
        
        <p class="hero-description">
            There are <strong>783 million hungry people</strong> in the world. But hunger is entirely solvable. 
            Every day, people around the world are sharing their meal, and the 
            <strong>United Nations World Food Programme</strong> is on the frontlines ensuring it reaches 
            those most in need. Imagine the collective impact we could have if we all shared the meal.
        </p>
    </div>
</div>
    <div class="card">
      <!-- Toggle Buttons -->
      <div class="toggle-container">
        <button id="onceBtn" class="toggle-button active">Once</button>
        <button id="monthlyBtn" class="toggle-button">Monthly 💛</button>
      </div>
    
      <!-- Main Donation Card (Dynamic) -->
      <div class="center-card">
        <h2>Donate <span id="donationType">once</span></h2>
        <p class="amount">₹<span id="donationAmount">150</span></p>
        <p class="impact" id="impactText">This can provide food to <strong>50 people</strong> in need</p>
      </div>
    
      <!-- Donation Options -->
      <div class="amount-options">
        <button class="donation-option" onclick="updateCard(500, 'This can provide food to <strong>7 people</strong> in need')">₹ 500</button>
        <button class="donation-option" onclick="updateCard(1000, 'This can provide food to <strong>16 people</strong> in need')">₹ 1000</button>
        <button class="donation-option selected" onclick="updateCard(1500, 'This can provide food to <strong>50 people</strong> in need')">₹ 1500</button>
        <button class="donation-option" onclick="updateCard(2000, 'This can provide food to <strong>100 people</strong> in need')">₹ 2000</button>
        <input type="number" id="customAmount" placeholder="Other amount" oninput="updateCustomCard()">
      </div>
    
      <input type="email" placeholder="Enter your email" class="email-input">
      <button onclick="window.location.href='donate_meals.php'" class="meals">Donate Meals</button>
      <button class="cta-button">Continue</button>
    </div>
</div>
<!-- Scroll to Top Button -->
<?php  include 'scrolling.php' ?>
<?php include 'footer.php' ?>
<script src="./js/donate.js"></script>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
