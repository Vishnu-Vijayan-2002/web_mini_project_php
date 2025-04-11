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
<body class="about-page">
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
    <p class="text-center text-muted second">Support with Food and Cash Donations</p>
    <p class="text-center">We're committed to ending hunger by encouraging everyday people to take simple steps — like sharing food or donating funds — that directly support those in need. Every small contribution can lead to a big impact.</p>
</div>

<div class="container">
    <div class="content">
        <p class="subheading">We believe in collective care</p>
        <h1>Together, we can combat hunger with food and funds</h1>
        <p class="description">
            Millions face hunger every day. But the solution is simple: sharing what we can. Whether it's a meal or a small amount of money, every act of giving helps someone in need. Together, we can make a real difference — no borders, no barriers.
        </p>
    </div>
</div>

<!-- Our Values -->
<div class="container2">
    <div class="title-section">
        <h1>Our Values</h1>
        <p>What drives our mission</p>
    </div>

    <div class="values-cards">
        <!-- Card 1 -->
        <div class="card">
            <div class="card-head">
                <img src="https://images.ctfassets.net/z0x29akdg5eb/6z7HwkK7cOuYgBMjVdM4Of/c610ddb395cb91f94cfbe3eefeb35150/WFP_STM_Values_Illustrations_Open.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Open and Honest">
                <h2>Transparency</h2>
            </div>
            <p>We ensure your donations — food or funds — are used for real impact and provide clear updates on where they go.</p>
        </div>

        <!-- Card 2 -->
        <div class="card">
            <div class="card-head">
                <img src="https://images.ctfassets.net/z0x29akdg5eb/6OGHTYlkmXGZYCd2yEvIzH/1f5dd6b10f633421e08bf7d061e228e3/WFP_STM_Values_Illustrations_Counts.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Every Meal Counts">
                <h2>Every Contribution Counts</h2>
            </div>
            <p>Whether it's one food package or a small cash gift, it makes a difference in someone's life today.</p>
        </div>

        <!-- Card 3 -->
        <div class="card">
            <div class="card-head">
                <img src="https://images.ctfassets.net/z0x29akdg5eb/4AOcGZNPlgUoD9G8Ekzqon/baa482532b732549395eca2638726635/WFP_STM_Values_Illustrations_Together.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Together">
                <h2>Unity in Action</h2>
            </div>
            <p>We believe in community-powered change — helping each other without needing to know names or places.</p>
        </div>
    </div>
</div>

<!-- Opportunities -->

<?php include 'scrolling.php' ?>
<?php include 'footer.php' ?>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</html>
