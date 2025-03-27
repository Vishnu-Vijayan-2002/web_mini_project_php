<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./volunteer.css">
    <title>Volunteer Application</title>
</head>
<body>

<section class="volunteer-container">
    <div class="card">
        <img src="./images/volunteer.jpg" alt="Volunteer Job">
        <div class="content">
            <h2>Join as a Volunteer</h2>
            <p>Help us distribute meals to those in need. Apply now and be a part of our mission.</p>

            <div class="actions_btn">
                <button onclick="toggleDetails()" class="btn more">Read more</button>
                <a href="volunteer_register.php" class="btn apply">Apply now</a>
                <a href="volunteer_status.php" class="btn status">Application Status</a> <!-- New Button -->
            </div>

            <!-- Hidden Section for Expanded Content -->
            <div id="details" class="hidden-details">
                <h3>🌟 Our Mission</h3>
                <p>We strive to combat hunger by efficiently distributing meals to underprivileged communities. Our goal is to create a sustainable and scalable system that ensures no one goes to bed hungry.</p>

                <h3>🚀 Our Vision</h3>
                <p>To build a world where food security is a reality for all, empowering local volunteers to make a lasting impact in their communities.</p>

                <h3>🛠️ Application Procedure</h3>
                <ol>
                    <li><strong>Register:</strong> Fill out the volunteer application form with your details.</li>
                    <li><strong>Verification:</strong> Our admin team will review your application and verify your credentials.</li>
                    <li><strong>Approval:</strong> Once verified, you'll be added to the volunteer team and notified via email.</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<script>
    // Smooth Toggle Function
    function toggleDetails() {
        const details = document.getElementById('details');

        // Toggle visibility class
        details.classList.toggle('active');

        // Smooth transition handling
        if (details.classList.contains('active')) {
            details.style.maxHeight = details.scrollHeight + "px";
        } else {
            details.style.maxHeight = "0";
        }
    }
</script>

</body>
</html>
