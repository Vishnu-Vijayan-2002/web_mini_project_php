<?php require_once 'includes/header.php'; // Includes new Bootstrap header ?>
<?php require_once 'includes/db_connect.php'; ?>

<!-- Hero Section (Styles defined in header <style> or custom CSS) -->
<section class="hero mb-5">
    <div class="hero-content">
        <h1>Share Your Plate, Share Your Heart</h1>
        <p>Connect with volunteers to donate your surplus food to those in need in our community.</p>
        <div class="hero-buttons">
            <!-- Use Bootstrap button classes -->
            <a href="donor_register.php" class="btn">Donate Food Now</a>
            <a href="volunteer_register.php" class="btn secondary">Become a Volunteer</a>
        </div>
    </div>
</section>

<!-- Support Our Cause (Cash Donation) Section -->
<section class="support-cause py-5 bg-warning bg-opacity-10">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Can't Donate Food? Support Our Cause!</h2>
        <p class="mb-4">Your financial contribution helps us cover logistics, food safety, and outreach. Every bit counts towards fighting hunger together.</p>
        <a href="cash_donate.php" class="btn btn-warning btn-lg px-4 fw-semibold">
            Donate Cash <i class="bi bi-currency-rupee ms-2"></i>
        </a>
    </div>
</section>

<!-- Request Help Section -->
<section class="request-help py-5 bg-light">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Need Help? Submit a Request</h2>
            <p>If you or someone you know needs urgent support (medical, education, etc.), submit a request with supporting documents and payment details. Our team will review and list approved requests for donors to help.</p>
            <a href="request_help.php" class="btn btn-danger btn-lg fw-semibold mt-2 me-2">
                Request Help <i class="bi bi-clipboard-plus ms-2"></i>
            </a>
            <a href="open_help_requests.php" class="btn btn-outline-success btn-lg fw-semibold mt-2">
                View Open Help Requests <i class="bi bi-list-check ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Impact Section -->
<section class="impact-section">
        <div class="impact-container">
            <!-- Left: World Map -->
            <div class="impact-map">
                <img class="img-fluid" src="./images/world3.png" alt="World Map" > <!-- Responsive image -->
            </div>

            <!-- Right: Impact Content -->
            <div class="impact-content">
                <h2>Our Impact So Far</h2>
                <p>FoodShare donations not only provide food in emergencies but also facilitate school feeding, nutrition support, cash transfers and resilience programs.</p>
                <a href="#" class="learn-more">Learn more <i class="bi bi-arrow-right-short"></i></a>
               </div>
           </div>
         <!-- /.container -->
         

        <?php
            // --- PHP Logic to Fetch Real Stats ---
            $totalMeals = 0; $totalDonors = 0; $totalVolunteers = 0; $statsError = false;
            try {
                $stmtMeals = $pdo->query("SELECT COUNT(*) FROM donations WHERE status = 'delivered'");
                $totalMeals = $stmtMeals->fetchColumn() * 5; // Estimate

                $stmtDonors = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'donor'");
                $totalDonors = $stmtDonors->fetchColumn();

                $stmtVolunteers = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'volunteer' AND is_approved = 1");
                $totalVolunteers = $stmtVolunteers->fetchColumn();
            } catch (PDOException $e) {
                error_log("Stats Query Failed: " . $e->getMessage());
                // Use placeholders if query fails
                $totalMeals = 5000; $totalDonors = 150; $totalVolunteers = 75; $statsError = true;
            }
        ?>

        <!-- Bottom: Stats Section -->
    <section class="impact-stats-section py-5">
        
        <div class="impact-stats">
             <?php if($statsError) echo '<p class="text-center text-danger small">Could not load live stats.</p>'; ?>
             <div class="row g-4">
                <div class="col-md-4 stat-item">
                    <span class="label">Meals Donated (Est.)</span>
                    <span class="count"><?php echo number_format($totalMeals); ?></span>
                </div>
                <div class="col-md-4 stat-item">
                     <span class="label">Generous Donors</span>
                    <span class="count"><?php echo number_format($totalDonors); ?></span>
                </div>
                <div class="col-md-4 stat-item">
                     <span class="label">Active Volunteers</span>
                    <span class="count"><?php echo number_format($totalVolunteers); ?></span>
                </div>
           </div>
        </div> <!-- /.impact-stats -->
        
     </section> <!-- /.impact-section -->
</section>


<!-- How It Works Section -->
<section class="working py-5" id="how-it-works"> <!-- Use py-5 for padding -->
    <div class="container">
        <h2>How It Works</h2>
        <div class="row g-4 justify-content-center"> <!-- Use g-4 for gutters, justify-content-center -->

            <div class="col-md-6 col-lg-3 d-flex align-items-stretch"> <!-- Step 1 Card -->
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="step-icon"><i class="bi bi-person-plus-fill"></i></div>
                        <h5 class="card-title">1. Donor Registers & Lists Food</h5>
                        <p class="card-text small">Generous individuals or businesses sign up and list details about the surplus food they wish to donate.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 d-flex align-items-stretch"> <!-- Step 2 Card -->
                 <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="step-icon"><i class="bi bi-hand-index-thumb-fill"></i></div>
                        <h5 class="card-title">2. Volunteer Accepts Task</h5>
                        <p class="card-text small">Registered and approved volunteers browse available donations and accept a pickup task.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3 d-flex align-items-stretch"> <!-- Step 3 Card -->
                 <div class="card text-center h-100">
                    <div class="card-body">
                         <div class="step-icon"><i class="bi bi-truck"></i></div>
                        <h5 class="card-title">3. Food is Collected & Delivered</h5>
                        <p class="card-text small">The volunteer collects the food from the donor and delivers it safely to designated distribution points or directly to those in need.</p>
                    </div>
                </div>
            </div>

             <div class="col-md-6 col-lg-3 d-flex align-items-stretch"> <!-- Step 4 Card -->
                 <div class="card text-center h-100">
                    <div class="card-body">
                         <div class="step-icon"><i class="bi bi-heart-fill"></i></div>
                        <h5 class="card-title">4. Impact is Made!</h5>
                        <p class="card-text small">Less food waste, more meals for the hungry. Everyone wins!</p>
                    </div>
                </div>
            </div>

        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

<!-- Add other sections like FAQs, App Badges using similar Bootstrap structure -->
<section id="faqs" class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4 fw-bold">Frequently Asked Questions</h2>
        <!-- Add Bootstrap Accordion component here for FAQs -->
         <div class="accordion" id="faqAccordion">
          <!-- FAQ Item 1 -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                What kind of food can I donate?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Generally, we accept perishable and non-perishable food items that are unopened or safely prepared (if cooked). Please check specific guidelines during donation listing or contact us. Safety is our priority.
              </div>
            </div>
          </div>

          <!-- FAQ Item 2 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                How do I become a donor or volunteer?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Simply register on our website as a donor or volunteer. Donors can list available food, and volunteers can accept pickup and delivery tasks after approval.
              </div>
            </div>
          </div>

          <!-- FAQ Item 3 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Is there a minimum amount of food required to donate?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                No, there is no minimum. Every contribution, big or small, helps someone in need. You can donate a single meal or a larger batch.
              </div>
            </div>
          </div>

          <!-- FAQ Item 4 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                How do I know my donation reached someone in need?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                You will receive a notification once your donation is delivered. You can also track the status in your dashboard.
              </div>
            </div>
          </div>

          <!-- FAQ Item 5 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingFive">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                What safety measures are in place for food handling?
              </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                All volunteers are trained in safe food handling and delivery. We follow strict guidelines to ensure food safety from pickup to delivery.
              </div>
            </div>
          </div>

          <!-- FAQ Item 6 -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="headingSix">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                Can I donate as a business or restaurant?
              </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                Yes! We welcome donations from individuals, businesses, and restaurants. Please register and list your available food items. Our team will coordinate the rest.
              </div>
            </div>
          </div>
        </div>
    </div>
</section>


<!-- Scroll to Top Button -->

<?php  include 'includes/scrolling.php' ?>

<!-- Footer -->
<?php require_once 'includes/footer.php'; // Includes closing main tag ?>