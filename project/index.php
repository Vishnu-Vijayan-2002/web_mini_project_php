<!DOCTYPE html>
<html lang="en">
<head>
    <!-- cdn-js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" sizes="32x32" href="./images/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="./images/logo.png">
    <link rel="stylesheet" href="./index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Feed people in need</title>
</head>
<body>
   <!-- header -->
    <?php include 'header.php'?>
    <!-- banner -->
    <div class="d-flex flex-column flex-md-row align-items-center p-5 bg-white border rounded shadow-sm banner">
        <div class="flex-grow-1 me-md-4 text-center text-md-start">
            <h1 class="fw-bold">Fight hunger with the ShareTheMeal app</h1>
            <p class="text-muted">With just a few taps on your phone, you can donate to someone in need, and the United Nations World Food Programme will deliver the meals. Help us end hunger by downloading the ShareTheMeal app today.</p>
            <div class="mt-3">
              <a href="#"><img src="https://sharethemeal.org/_static/badges/store-android-en.svg" alt="Google Play" class="store-badge me-2"></a>
              <a href="#"><img src="https://sharethemeal.org/_static/badges/store-ios-en.svg" alt="App Store" class="store-badge"></a>
             </div>
        </div>
        <div class="mt-4 mt-md-0">
            <img src="./images/banner.jpg" alt="ShareTheMeal App" class="img-fluid rounded">
        </div>
    </div>
    <!-- sign up -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
       <?php include 'signup.php' ?>
    </div>
  </div>
</div>
<!-- take action -->
 <div class="take">
    <h1 class="text-center take_action">Take action</h1>
    <p class="text-center text-muted">Simply browse through our fundraising goals and donate to the causes that matter to you.</p>
   </div>
  <div class="main_urgent_card">
     <div class="urgent_card">
         <div class="left_img">
             <img style="width: 550px;" class="img-fluid" src="./images/card.jpeg" alt="">
         </div> 
         <div class="main_right_content">
          <div class="right_content">
            <h1>Give emergency aid in Palestine</h1>
            <p>Shared meals will provide emergency food assistance to families in Palestine.</p>
            <div class="btn_group">
               <button onclick="window.location.href='donate.php'" class="left">Read more</button>
               <button onclick="window.location.href='donate.php'" class="right">Donete now</button>
            </div>
           </div>
          </div>
         </div>
     </div>  
     <!-- crads -->
     <div class="card-container">
      <div class="donation-card">
        <img src="./images/card1.jpg" alt="Palestine Aid">
        <div class="card-content">
            <h3>Palestine: Donate life-saving food</h3>
            <a href="#">Read more</a>
            <p class="supporters"><i class="fa fa-users"></i> 421,614 supporters</p>
            <p class="meals"><strong>34,502,711 meals</strong></p>
            <div id="progress-bar" class="progress-bar">
                <div id="progress" class="progress" style="width: 80%;"></div>
            </div>
        </div>
     </div>
     <div class="donation-card">
        <img src="./images/card2.jpg" alt="Sudan Aid">
        <div class="card-content">
            <h3>Sudan: Help families fleeing conflict</h3>
            <a href="#">Read more</a>
            <p class="supporters"><i class="fa fa-users"></i> 63,798 supporters</p>
            <p class="meals"><strong>2,537,739 meals</strong></p>
            <div id="progress-bar" class="progress-bar">
                <div id="progress" class="progress" style="width: 84%;"></div>
            </div>
        </div>
     </div>
     <div class="donation-card">
        <img src="./images/card3.jpg" alt="Syria Aid">
        <div class="card-content">
            <h3>Syria: Share with families in need</h3>
            <a href="#">Read more</a>
            <p class="supporters"><i class="fa fa-users"></i> 61,343 supporters</p>
            <p class="meals"><strong>2,076,559 meals</strong></p>
            <div id="progress-bar" class="progress-bar">
                <div id="progress" class="progress" style="width: 90%;"></div>
            </div>
        </div>
    </div>
 </div>
<div class="center_btn"> <button onclick="window.location.href='Fundraising.php'" class="btn">see all</button></div>
<!-- world section -->
<section class="impact-section">
        <div class="impact-container">
            <!-- Left: World Map -->
            <div class="impact-map">
                <img class="img-fluid" src="./images/world3.png" alt="World Map">
            </div>

            <!-- Right: Impact Content -->
            <div class="impact-content">
                <h2>Our impact to date</h2>
                <p>ShareTheMeal donations not only provide life-saving food in emergencies but also facilitate school feeding, nutrition support, cash transfers and resilience programs all over the world.</p>
                <a href="#" class="learn-more">Learn more ></a>
            </div>
        </div>

        <!-- Bottom: Stats Section -->
        <div class="impact-stats">
            <div class="stat-box">
                <h3>257,491,424 meals</h3>
                <p>shared</p>
            </div>
            <div class="stat-box">
                <h3>1,743,827 supporters</h3>
                <p>fighting hunger</p>
            </div>
            <div class="stat-box">
                <h3>127 goals</h3>
                <p>completed</p>
            </div>
            <div class="stat-box green-text">
                <h3>+ 198,586</h3>
                <p>in the last day</p>
            </div>
            <div class="stat-box green-text">
                <h3>+ 9,830</h3>
                <p>in the last day</p>
            </div>
            <div class="stat-box green-text">
                <h3>+ 3</h3>
                <p>in the last 90 days</p>
            </div>
        </div>
    </section>
<!-- volunteer session -->
<?php  include 'volunteer.php' ?>
<!-- mail section-->
<div class="main_mail_section">
  <div class="newsletter-container">
          <div class="newsletter-image">
              <img class="mail_img" src="./images/mail.png" alt="Newsletter Icon">
          </div>
          <div class="newsletter-content">
              <h2>Got a taste for fighting hunger?</h2>
              <p>Connect with your impact by signing up for our newsletter.</p>
              <div class="newsletter-form">
                  <input type="email" placeholder="Enter your email">
                  <button>➤</button>
              </div>
          </div>
      </div>
</div>
<!-- Scroll to Top Button -->
<?php  include 'scrolling.php' ?>
<!-- footer -->
    <?php include 'footer.php' ?>
</body>
<style>
  .store-badge {
    width: 150px;
    height: auto; 
}
</style>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./js/indexs_cripts.js"></script>
</html>