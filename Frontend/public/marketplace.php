<?php
/* This page will host the search if a user is signed in. */
require(__DIR__ . "/../partials/nav.php");
logged_in(true);
$marketRequest = new DBRequests();
$market_arr = $marketRequest->getMarket();
//echo "\n market_arr: \n";
//var_dump($market_arr);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <!-- Favicon-->
  <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
  <!-- Bootstrap icons-->

  <!-- Core theme CSS (includes Bootstrap)-->
  <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
  <div class="card bg-light">
    <div class="card-body text-center">
      <img src="https://cdn.discordapp.com/attachments/1065331325546020967/1086880328053948527/AUDIONOOK_blk.png" alt="AN logo" class="img-fluid mx-auto d-block" style="max-width: 20%; height: auto;">
    </div>
  </div>
  </header>
  <!-- Header-->
  <!-- Search-->
  <div class="container">
    <div class="row height d-flex justify-content-center align-items-center justify-content-md-center">
      <div class="col-md-4">
        <div class="search">
          <i class="fa fa-search"></i>
          <input type="text" class="form-control" placeholder="Search...">
          <button class="btn btn-primary ml-auto" style="background-color: #28a745; border-color: #28a745;">Search</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Search-->
  <!-- Section-->
  <?php foreach ($market_arr as $products) : ?>

    <section style="background-color: #eee;">
      <div class="container py-5">
        <div class="row justify-content-center mb-3">
          <div class="col-md-12 col-xl-10">
            <div class="card shadow-0 border rounded-3">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                    <div class="bg-image hover-zoom ripple rounded ripple-surface">
                      <!-- Product image-->
                      <img src="<?php echo stripslashes(htmlspecialchars($products['cover_image'])) ?>" onerror="this.src='https://tinyurl.com/5n7fs4w8';" class="w-100" />
                      <a href="#!">
                        <div class="hover-overlay">
                          <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                        </div>
                      </a>
                    </div>
                  </div>
                  <div class="col-md-6 col-lg-6 col-xl-6">
                    <!-- Product Title-->
                    <h5><?php echo ($products['title']); ?></h5>
                    <div class="d-flex flex-row">
                      <!-- Potential Ratings
                <div>
                  <b-form-rating v-model="value"></b-form-rating>
                  <p class="mt-2">Value: <?php ?></p>
               </div> -->
                      <!-- Product condition-->
                      <span>Format: <?php echo $products['format']; ?></span>
                    </div>
                    <!-- Product genre loop and create more-->

                    <div class="mt-1 mb-0 text-muted small">
                      <span><?php echo $products['genres']; ?></span>
                      <span class="text-primary"> </span>
                    </div>

                    <!-- Product country-->
                    <div class="mb-2 text-muted small">
                      <?php echo $products['item_condition'] ?>
                    </div>
                    <!-- Product seller comments-->
                    <p class="mb-4 mb-md-0">
                      <?php echo $products['item_description']; ?>
                    </p>
                  </div>
                  <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                    <div class="d-flex flex-row align-items-center mb-1">
                      <!-- Product price-->
                      <h4 class="mb-1 me-1">$<?php echo $products['price']; ?></h4>
                    </div>
                    <!-- Product shipping-->
                    <h6 class="text-success">Shipping: $6.99</h6>
                    <div class="d-flex flex-column mt-4">
                      <div class="d-flex flex-row align-items-center mb-1">
                        <!-- Seller ID-->
                        <h4 class="mt-1 mb-0 text-muted small">Seller username: <?php echo $products['username']; ?></h4>
                      </div>
                      <div class="d-flex flex-row align-items-center mb-1">
                        <h4 class="mt-1 mb-0 text-muted small">Item Id: <?php echo $products['id']; ?></h4>
                      </div>
                      <div class="d-flex flex-row align-items-center mb-1">
                        <h4 class="mt-1 mb-0 text-muted small">Date Listed: <?php echo $products['created']; ?>/5</h4>
                      </div>
                      <!-- Add to cart button-->
                      <form method="POST" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $products['id']; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo get_user_id(); ?>" />
                        <input type="hidden" name="action" value="add" />
                        <input type="submit" id="blue-button" class="btn" value="Add to Cart" />
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>



      </style>
    </section>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>
<?php
include('footer.php');
?>