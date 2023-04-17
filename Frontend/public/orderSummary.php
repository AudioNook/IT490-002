<?php
require(__DIR__ . "/../partials/nav.php");


#14171a
  
$subtotal = 0;
$shipping = 6.99;
$subtotal = 0;
$orderNum =0;
$email ="lrs25@njit.edu";
$total = $shipping + $subtotal;

?>

<section class="h-100 h-custom" style="background-color: #eee;">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-8 col-xl-6">
        <div class="card border-top border-bottom border-3" style="border-color: #14171a !important;">
          <div class="card-body p-5">

            <p class="lead fw-bold mb-5" style="color: #14171a;">Thank You For Your Purchase!</p>

            <div class="row">
              <div class="col mb-3">
                <p class="small text-muted mb-1">Date</p>
                <p> <?php echo date("m-d-Y"); ?></p>


              </div>
              <div class="col mb-3">
                <p class="small text-muted mb-1">Order No.</p>
                <p> <?php echo htmlspecialchars($orderNum); ?></p>
              </div>
            </div>

            <div class="mx-n5 px-5 py-4" style="background-color: #f2f2f2;">
              <div class="row">
                <div class="col-md-8 col-lg-9">
                  <p>Subtotal </p>
                </div>
                <div class="col-md-4 col-lg-3">
                <p> $<?php echo htmlspecialchars($subtotal); ?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8 col-lg-9">
                  <p class="mb-0">Shipping</p>
                </div>
                <div class="col-md-4 col-lg-3">
                  <!-- shipping wont change regardlesss of oder  -->
                  <p class="mb-0"> $<?php echo htmlspecialchars($shipping); ?>
</p>
                </div>
              </div>
            </div>

            <div class="row my-4">
              <div class="col-md-4 offset-md-8 col-lg-3 offset-lg-9">
              <p class="mb-0">Total</p>
                <p class="lead fw-bold mb-0" style="color: #14171a;">$<?php echo htmlspecialchars($total); ?></p>
              </div>
            </div>

            <p class="lead fw-bold mb-4 pb-2" style="color: #14171a;">Tracking Order</p>


            <div class="row">
              <div class="col-lg-12">

              <p class="mb-0">Check email for tracking updates:</p>
              <p class="lead fw-bold mb-0" style="color: #14171a;"><?php echo htmlspecialchars($email); ?></p>



              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
include('footer.php');
?>
</section>