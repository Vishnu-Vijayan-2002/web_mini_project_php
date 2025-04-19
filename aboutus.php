<?php require_once 'includes/header.php'; ?>
<style>
   /* Pop hover effect only */
.value-box {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.value-box:hover {
  transform: scale(1.05);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
}

</style>


<div class="container py-5">
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="text-center mb-4">
               <h1 class="display-5 fw-bold" style="color: #ff8c00;">Our Purpose</h1>
                <p class="lead text-muted">Support with Food and Cash Donations</p>
                <p>We're committed to ending hunger by encouraging everyday people to take simple steps — like sharing food or donating funds — that directly support those in need. Every small contribution can lead to a big impact.</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mb-5 mt-4">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <p class="text-primary fw-semibold mb-1">We believe in collective care</p>
                            <h2 class="fw-bold mb-3">Together, we can combat hunger with food and funds</h2>
                            <p class="mb-0">Millions face hunger every day. But the solution is simple: sharing what we can. Whether it's a meal or a small amount of money, every act of giving helps someone in need. Together, we can make a real difference — no borders, no barriers.</p>
                        </div>
                        <div class="col-md-5 text-center d-none d-md-block">
                            <img src="images/Feed.jpg" alt="World Map" class="img-fluid rounded-3" style="max-width: 300px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 
   <!-- Full-width section with background color -->
<section class="text-center py-5 w-100" style="background-color: #f9f9f9; margin-left: 0; margin-right: 0;">
  <div class="container">
    <h2 class="fw-bold" style="color: #ff8c00;">Our Values</h2>
    <p class="text-muted">What drives our mission</p>
<div class="row g-4 justify-content-center mt-4">
      <!-- Box 1 -->
      <div class="col-12 col-sm-6 col-lg-3 d-flex justify-content-center">
        <div class="value-box animate-pop bg-white shadow rounded p-3 mx-auto" style="max-width: 250px;">
          <img src="https://images.ctfassets.net/z0x29akdg5eb/6z7HwkK7cOuYgBMjVdM4Of/c610ddb395cb91f94cfbe3eefeb35150/WFP_STM_Values_Illustrations_Open.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Open and Honest" class="mb-2 mx-auto d-block">
          <h5 class="text-primary fw-bold">Transparency</h5>
          <p>We ensure your donations — food or funds — are used for real impact and provide clear updates on where they go.</p>
        </div>
      </div>
      <!-- Box 2 -->
      <div class="col-12 col-sm-6 col-lg-3 d-flex justify-content-center">
        <div class="value-box animate-pop bg-white shadow rounded p-3 mx-auto" style="max-width: 250px;">
          <img src="https://images.ctfassets.net/z0x29akdg5eb/6OGHTYlkmXGZYCd2yEvIzH/1f5dd6b10f633421e08bf7d061e228e3/WFP_STM_Values_Illustrations_Counts.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Every Meal Counts" class="mb-2 mx-auto d-block">
          <h5 class="text-primary fw-bold">Every Contribution Counts</h5>
          <p>Whether it's one food package or a small cash gift, it makes a difference in someone's life today.</p>
        </div>
      </div>
      <!-- Box 3 -->
      <div class="col-12 col-sm-6 col-lg-3 d-flex justify-content-center">
        <div class="value-box bg-white shadow rounded p-3 mx-auto" style="max-width: 250px;">
          <img src="https://images.ctfassets.net/z0x29akdg5eb/4AOcGZNPlgUoD9G8Ekzqon/baa482532b732549395eca2638726635/WFP_STM_Values_Illustrations_Together.png?w=102&h=102&fit=fill&q=80&fm=avif" alt="Together" class="mb-2 mx-auto d-block">
          <h5 class="text-primary fw-bold">Unity in Action</h5>
          <p>We believe in community-powered change — helping each other without needing to know names or places.</p>
        </div>
      </div>
    </div>
  </div>
</section>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

<?php require_once  'includes/scrolling.php'; ?>
<?php require_once 'includes/footer.php'; ?> 

