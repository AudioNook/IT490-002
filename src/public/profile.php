<?php
require(__DIR__ . "/../partials/nav.php");

// Check if user is logged in

logged_in(true);

// User is logged in, show the forum page
?>
<html>
<head>
<script>
  //validateJWT();
</script>
    <title>My Profile</title>
</head>
<body>
<section style="background-color: #eee;">
  <div class="container py-5">
    <div class="row">
      <div class="col">
        <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
        <h5 class="my-3">My Profile</h5>
        </nav>
      </div>
    </div>
      <div class="col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Username</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">$username</p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0">$email</p>
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
</html>


<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
    <!-- Footer -->
    <footer class="text-center text-lg-start bg-light text-muted">
      <!-- Section: Social media -->
      <section class="d-flex justify-content-center justify-content-lg-between p-4 border-bottom">
      </section>

      <section>
        <div class="container text-center text-md-start mt-5">
          <!-- Grid row -->
          <div class="row mt-3">
            <!-- Grid column -->
            <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
              <!-- Content -->
              <h6 class="text-uppercase fw-bold mb-4">
                <i class="fas fa-gem me-3"></i>About Us
              </h6>
              <p>
                AudioNook is an online marketplace for buyers and sellers of physical music formats, from vinyl records to cassette tapes and audio equipment, like turntables. 
                Our platform serves music collectors, enthusiasts, and shops looking to expand their online presence.
              </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
              <!-- Links -->
              <h6 class="text-uppercase fw-bold mb-4">
                Products
              </h6>
              <p>
                <a href="#!" class="text-reset">CDs</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Vinyl</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Cassette Tapes</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Turntables</a>
              </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
              <!-- Links -->
              <h6 class="text-uppercase fw-bold mb-4">
                Useful Links
              </h6>
              <p>
                <a href="#!" class="text-reset">Login</a>
              </p>
              <p>
                <a href="#!" class="text-reset">Register</a>
              </p>
            </div>
            <!-- Grid column -->

            <!-- Grid column -->
            <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
              <!-- Links -->
              <h6 class="text-uppercase fw-bold mb-4">Contact</h6>
          <p><i class="fas fa-home me-3"></i> Newark, NJ 07103, US</p>
          <p>
            <i class="fas fa-envelope me-3"></i>
            info@audionook.com
          </p>
          <p><i class="fas fa-phone me-3"></i> + 01 234 567 88</p>
        </div>
        <!-- Grid column -->
      </div>
      <!-- Grid row -->
    </div>
  </section>
  <!-- Section: Links  -->

  <!-- Copyright -->
  <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
    © 2023 Copyright:
    <a class="text-reset fw-bold" href="#">audionook.com</a>
  </div>
  <!-- Copyright -->
</footer>
<!-- Footer -->
</html>