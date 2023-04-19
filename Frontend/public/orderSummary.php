<?php
require(__DIR__ . "/../partials/nav.php");

$user_id = get_user_id();
if (isset($_POST['action'])) {
  $action = strtolower(trim(htmlspecialchars($_POST['action'])));
  if (!empty($action)) {
    if (isset($_POST['product_id'])) {
      $product_id = $_POST['product_id'];
      $cartOpt = new DBRequests();
      if (array_key_exists('cart_id', $_POST)) {
        $cart_id = $_POST['cart_id'];
        $response = $cartOpt->doCart($user_id, $product_id, $action, $cart_id);
        redirect("profile.php");
      } else {
        $response = $cartOpt->doCart($user_id, $product_id, $action);
        redirect("profile.php");
      }
    }
  }
}
$getCart = new DBRequests();
$response = $getCart->doCart($user_id);
if (isset($response['code']) && $response['code'] == 200) {
  $cart = $response['cart'];
} else {
  $cart = [];
}
$subtotal = 0.00;
$total = 0.00;

$getCreds = new DBRequests();
$userInfo = $getCreds->getByUserId($user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Responsive Order Confirmation</title>
</head>

<body>
  <!-- TODO grey part -->
  <section class="h-100 h-custom relative" style="background-color: #eee;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-lg-8 col-xl-6">
          <div class="card border-top border-bottom border-3" style="border-color: #14171a !important;">
            <div class="card-body p-5">

              <p class="lead fw-bold mb-5" style="color: #14171a;">Thank You For Your Purchase!</p>

              <div class="row">
                <div class="col mb-3">
                  <p class="small text-muted mb-1">Date</p>
                  <p>
                    <?php echo date("m-d-Y"); ?>
                  </p>


                </div>
                <div class="col mb-3">
                  <p class="small text-muted mb-1">Order No.</p>
                  <p>
                    0245504156780015819297
                  </p>
                </div>
              </div>

              <div class="mx-n5 px-5 py-4 overflow:scroll" style="background-color: #f2f2f2;">
                <?php if (count($cart) > 0): ?>
                  <?php foreach ($cart as $c): ?>
                    <div class="row mb-3">
                      <div class="col-md-2 col-lg-2 col-xl-2">
                        <img src="<?php echo stripslashes(htmlspecialchars($c['cover_image'])) ?>"
                          class="img-fluid rounded-3" alt="Cotton T-shirt">
                      </div>
                      <div class="col-md-6 col-lg-6 col-xl-6">
                        <p class="lead fw-normal mb-2">
                          <?php echo ($c["name"]); ?>
                        </p>
                        <p class="text-muted">
                          <span>Product ID:</span>
                          <?php echo ($c["product_id"]); ?>
                        </p>
                      </div>
                      <div class="col-md-4 col-lg-4 col-xl-4">
                        <p>$
                          <?php echo (float) $c["unit_price"]; ?>
                        </p>
                      </div>
                    </div>
                    <?php $subtotal += (float) $c["unit_price"]; ?>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="card mt-3">
                    <div class="card-body">
                      No Order
                    </div>
                  </div>
                <?php endif; ?>
                <div class="row">
                  <div class="col-md-8 col-lg-9">
                    <p class="mb-0">Shipping:</p>
                    <p class="mb-0">Subtotal:</p>
                    <p class="mb-0">Total:</p>
                  </div>
                  <div class="col-md-4 col-lg-3">
                    <p class="mb-0">$6.99</p>
                    <?php $total = $subtotal + 6.99; ?>
                    <p class="mb-0">$
                      <?php echo ($subtotal); ?>
                    </p>
                    <p class="mb-0">$
                      <?php echo ($total); ?>
                    </p>
                  </div>
                </div>
              </div>

              <div class="row my-4">
              </div>
              <div class="row">
                <div class="col mb-3">

                  <p class="mb-0">Check email for tracking updates:</p>
                  <p class="lead fw-bold mb-0" style="color: #14171a;">
                    <?php echo $userInfo['email']; ?>
                  </p>

                </div>
                <div class="col mb-3">
                  <form method="POST">
                    <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($c["id"]); ?>" />
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($c["product_id"]); ?>" />
                    <!-- Add this line -->
                    <button type="submit" class="btn btn-outline-dark"> Let's take you back home</button>
                  </form>
                  </p>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <?php
  include('footer.php');
  ?>