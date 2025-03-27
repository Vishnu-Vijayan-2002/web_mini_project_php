<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./volunteer_register.css">
    <title>Volunteer Registration</title>
</head>
<body>

<section class="register-container">
    <div class="form-card">
        <h2>Volunteer Registration</h2>
        <p>Join us and make a difference by helping distribute meals to those in need.</p>

        <form action="submit_volunteer.php" method="POST">
            
            <!-- Full Name -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- Phone Number -->
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>

            <!-- Aadhaar Number -->
            <div class="form-group">
                <label for="aadhaar">Aadhaar Number</label>
                <input type="text" id="aadhaar" name="aadhaar" placeholder="Enter your Aadhaar number" required 
                       pattern="^\d{4}\s\d{4}\s\d{4}$" title="Aadhaar format: XXXX XXXX XXXX">
            </div>

            <!-- Availability -->
            <div class="form-group">
                <label for="availability">Availability</label>
                <select id="availability" name="availability" required>
                    <option value="weekdays">Weekdays</option>
                    <option value="weekends">Weekends</option>
                    <option value="full-time">Full-time</option>
                </select>
            </div>

            <!-- Skills or Experience -->
            <div class="form-group">
                <label for="skills">Skills or Experience</label>
                <textarea id="skills" name="skills" placeholder="Mention relevant skills or experience" rows="4"></textarea>
            </div>

            <button type="submit" class="btn submit-btn">Register Now</button>
        </form>
        
        <a href="index.php" class="back-btn">← Back to Home</a>
    </div>
</section>

</body>
</html>
