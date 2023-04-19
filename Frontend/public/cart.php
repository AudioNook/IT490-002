<?php
require(__DIR__ . "/../partials/nav.php");
logged_in(true);
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
            } else {
                $response = $cartOpt->doCart($user_id, $product_id, $action);
            }
        }
    }
}
$getCart = new DBRequests();
$response = $getCart->doCart($user_id);
if ($response['code'] == 200) {
    $cart = $response['cart'];
} else {
    $cart = [];
}
$subtotal = 0;
?>

<section class="h-100" style="background-color: #eee;">
    <div class="container h-100 py-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-normal mb-0 text-black">Shopping Cart</h3>
                    <div>
                        <p class="mb-0"><span class="text-muted">Sort by:</span> <a href="#!" class="text-body">price:<i class="fas fa-angle-down mt-1"></i></a></p>
                    </div>
                </div>
                <div class="card rounded-3 mb-4">

                    <?php $total = 0; ?>

                    <div class="card-body p-4">
                        <?php if (count($cart) > 0) : ?>
                            <?php foreach ($cart as $c) : ?>

                                <div class="card">
                                    <div class="row d-flex justify-content-between align-items-center">
                                        <div class="col-md-2 col-lg-2 col-xl-2">
                                            <img src="<?php echo stripslashes(htmlspecialchars($c['cover_image'])) ?>" class="img-fluid rounded-3" alt="Cotton T-shirt">
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-xl-3">
                                            <p class="lead fw-normal mb-2">
                                                <?php echo htmlspecialchars($c["name"]); ?>
                                            </p>
                                            <span class="text-muted">Product ID:</span>
                                            <?php echo htmlspecialchars($c["product_id"]); ?>
                                            </p>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                            <form method="POST" class="d-inline-flex">
                                                <input type="hidden" name="cart_id" value="<?php echo (int)$c["id"]; ?>" />
                                                <input type="hidden" name="action" value="update" />
                                                <input type="number" readonly class="form-control small-input" id="disabledTextInput" name="desired_quantity" value="<?php echo 1; ?>" min="0" max="<?php echo 1; ?>" />
                                                <input type="submit" class="btn btn-primary btn-sm" value="Update" disabled />
                                            </form>
                                        </div>
                                        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                            <p><span class="text-muted">Price: $ </span><?php echo htmlspecialchars($c["unit_price"]); ?>
                                        </div>
                                        <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                            <form method="POST">
                                                <input type="hidden" name="cart_id" value="<?php echo htmlspecialchars($c["id"]); ?>" />
                                                <input type="hidden" name="action" value="delete" />
                                                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($c["product_id"]); ?>" /> <!-- Add this line -->
                                                <button type="submit" class="btn btn-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-x" viewBox="0 0 16 16">
                                                        <path d="M7.354 5.646a.5.5 0 1 0-.708.708L7.793 7.5 6.646 8.646a.5.5 0 1 0 .708.708L8.5 8.207l1.146 1.147a.5.5 0 0 0 .708-.708L9.207 7.5l1.147-1.146a.5.5 0 0 0-.708-.708L8.5 6.793 7.354 5.646z" />
                                                        <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php $subtotal += (int)$c["subtotal"]; ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="card mt-3">
                                <div class="card-body">
                                    Cart Empty
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body p-4 d-flex flex-row">
                        <div class="form-outline flex-fill">
                            <input readonly type="text" id="form1" placeholder="Enter Promo" class="form-control form-control-lg" />
                            <label class="form-label" for="form1">Promotion code</label>
                        </div>
                        <button type="button" class="btn btn-outline-dark btn-lg ms-3">Apply</button>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-4 d-flex flex-row">
                        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                            <h4 class="mb-0" type='text' value='sub' id='sub'> Total:</h4>
                            <h5 class="mb-0" type='text' value='subtotal' id='subtotal'>
                                $<?php echo htmlspecialchars($subtotal); ?>
                            </h5>
                        </div>
                        <div style="position:relative; left:100px; top:2px;">

                            <button type="button" class="btn btn-dark echo get_url('checkout.php')">Continue to Check Out</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>