<!DOCTYPE html>
<html lang="en">
<head>
    <!-- CDN JS and CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./header.css">
    <title>Responsive Navbar</title>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container-fluid">
    <a class="navbar-brand"  onclick="window.location.href='index.php'" href="#"><img src="https://sharethemeal.org/_static/icons/stm-logo.svg" alt="Logo"></a>
    
    <!-- Navbar Toggler -->
    <button class="navbar-toggler" type="button" id="navbarToggler">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul  class="navbar-nav me-auto">
        <li class="nav-item">
          <a id="nav-link" onclick="window.location.href='Fundraising.php'" class="nav-link" href="#">Fundraising goals</a>
        </li>
        <li class="nav-item">
          <a id="nav-link"   onclick="window.location.href='about.php'" class="nav-link" href="#">About us</a>
        </li>
        <li class="nav-item">
          <a id="nav-link" onclick="window.location.href='FAQs.php'" class="nav-link" href="#">FAQs</a>
        </li>
      </ul>
      
      <!-- Buttons (Clicking will close navbar) -->
      <div class="d-flex">
        <button id="sign_btn" class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#staticBackdrop" onclick="closeNavbar()">Sign in</button>
        <button type="button" onclick="window.location.href='donate.php'" class="btn btn-warning" onclick="closeNavbar()">Donate</button>
      </div>
    </div>
  </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
