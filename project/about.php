<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="icon" sizes="32x32" href="./images/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="./images/logo.png">
    <link rel="stylesheet" href="about.css">
</head>
<body>
<?php include 'header.php'?>
       <!-- sign up -->
       <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
       <?php include 'signup.php' ?>
    </div>
  </div>
</div>
<div class="main">
    <h1 class="text-center head">Our Purpose</h1>
    <p class="text-center text-muted second">Empowering people to end global hunger</p>
    <p class="text-center">We’re here to end global hunger. And we’re guessing you are too. That’s why we were founded in 2015 under the United Nations World Food Programme — to make fighting hunger accessible to everyone. Because with just AED 3 and a few taps on your phone, you can share your meal with someone in need.</p>
</div>
<div class="container">
    <div class="image-wrapper">
        <img src="https://images.ctfassets.net/z0x29akdg5eb/6VnhBZx3jaRKjcBBdAZDGb/349b6786025b078c9e719191a4abdf8f/Cover_Photo_November_12.JPG?w=741&h=494&fit=fill&q=80&fm=avif" alt="Mother and child" class="banner">
    </div>

    <div class="content">
        <p class="subheading">There's one thing we'll never stop believing in</p>
        <h1>Together, we can be the generation<br>that ends global hunger.</h1>
        
        <p class="description">
            There are <strong>783 million hungry people</strong> in the world. But hunger is entirely solvable. 
            Every day, people around the world are sharing their meal, and the 
            <strong>United Nations World Food Programme</strong> is on the frontlines ensuring it reaches 
            those most in need. Imagine the collective impact we could have if we all shared the meal.
        </p>
    </div>
</div>
<!-- Our Values -->
<div class="container2">

    <!-- Title Section -->
    <div class="title-section">
        <h1>Our Values</h1>
        <p>A few important things we live by</p>
    </div>

    <!-- Values Cards -->
    <div class="values-cards">

        <!-- Card 1 -->
        <div class="card">
            <div  class="card-head">
                <img src="https://images.ctfassets.net/z0x29akdg5eb/6z7HwkK7cOuYgBMjVdM4Of/c610ddb395cb91f94cfbe3eefeb35150/WFP_STM_Values_Illustrations_Open.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Open and Honest">
                <h2>Open and honest</h2>
            </div>
            <p>We want you to know how your donation is used and the impact you’re having around the world.</p>
        </div>

        <!-- Card 2 -->
        <div class="card">
           <div class="card-head">
                <img src="https://images.ctfassets.net/z0x29akdg5eb/6OGHTYlkmXGZYCd2yEvIzH/1f5dd6b10f633421e08bf7d061e228e3/WFP_STM_Values_Illustrations_Counts.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Shared Meal">
                <h2>Every shared meal counts</h2>
           </div>
            <p>Give what you can — whether it’s one meal or one year of meals — and know that it makes a difference.</p>
        </div>

        <!-- Card 3 -->
        <div class="card">
          <div class="card-head">
                <img src="https://images.ctfassets.net/z0x29akdg5eb/4AOcGZNPlgUoD9G8Ekzqon/baa482532b732549395eca2638726635/WFP_STM_Values_Illustrations_Together.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Together">
                <h2>We’re in it together</h2>
          </div>
            <p>We want fighting hunger to be inclusive so that anyone, anywhere, can share the meal.</p>
        </div>

    </div>

</div>
<!--opportunity-->
    <!-- Title -->
    <div class="opportunity-header">
        <h1>Our Opportunities</h1>
        <p>Make changing the world your day job</p>
    </div>
<section class="opportunity-wrapper">

    <!-- Content Section -->
    <div class="opportunity-content">

        <!-- Image -->
        <div class="opportunity-image">
            <img src="https://media.istockphoto.com/id/496276728/photo/group-of-happy-gypsy-indian-children-desert-village-india.jpg?s=612x612&w=0&k=20&c=p7WwGJTF0rIbBbj2Gt4TGAol4nbmjjHekXc9qxkk3Os=" alt="Children Smiling">
        </div>

        <!-- Text Section -->
        <div class="opportunity-info">
            <h2>Work with us</h2>
            <p>Does this sound like your dream job? For us it is. Join our team and bring your passion to work every day.</p>
            
            <a href="#" class="opportunity-btn">Explore opportunities</a>
        </div>

    </div>

</section>
<!-- Scroll to Top Button -->
    <?php  include 'scrolling.php' ?>
    <?php include 'footer.php' ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
