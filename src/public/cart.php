<?php
require(__DIR__ . "/../partials/nav.php");


$action = $_POST["action"];
$cart = array(
    array(
        'product_id' => 1,
        'name' => 'Cassette',
        'price' => 0,
        'quantity' => 1,
        'subtotal' => 0
    ),
    array(
        'product_id' => 2,
        'name' => 'CD',
        'price' => 10.99,
        'quantity' => 2,
        'subtotal' => 21.98
    ),
    array(
        'product_id' => 3,
        'name' => 'Vinyl',
        'price' => 15.99,
        'quantity' => 1,
        'subtotal' => 15.99
    )
);



?>
<section class="h-100" style="background-color: #eee;">
    <div class="container h-100 py-5">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-10">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-normal mb-0 text-black">Shopping Cart</h3>
                    <div>
                        <p class="mb-0"><span class="text-muted">Sort by:</span> <a href="#!" class="text-body">price <i
                                    class="fas fa-angle-down mt-1"></i></a></p>
                    </div>
                </div>
                <div class="card rounded-3 mb-4">

                    <!-- start for each -->
                    <?php $total = 0; ?>
                    <?php foreach ($cart as $item): ?>

                        <div class="card-body p-4">

                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="col-md-2 col-lg-2 col-xl-2">
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img1.webp"
                                        class="img-fluid rounded-3" alt="Cotton T-shirt">
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-3">
                                    <!-- display product name -->
                                    <p class="lead fw-normal mb-2">PName:
                                        <?php htmlspecialchars($item["name"]); ?>
                                    </p>
                                    <!-- end product name -->
                                    <span class="text-muted">Product ID:</span>
                                    <?php htmlspecialchars($item["product_id"]); ?>
                                    </p>
                                </div>
                                <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                    <button class="btn btn-link px-2"
                                        onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                <!-- if number is less than 1, it is deleted from the cart -->
                                    <input id="form1" min="0" max="1" name="quantity" value="1" type="number"
                                        class="form-control form-control-sm" />

                                    <button class="btn btn-link px-2"
                                        onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <!-- display price -->
                                <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                <p><span class="text-muted">Price: </span><?php htmlspecialchars($item["price"]); ?> 
                                </div>
                                <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                    <a href="#!" class="btn btn-danger"></i></a>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <!-- if cart is empty -->
                <?php if (count($cart) == 0): ?>
                    <tr>
                        <td colspan="100%">Cart Empty</td>
                    </tr>
                <?php endif; ?>
                <div class="card mb-4">
                    <div class="card-body p-4 d-flex flex-row">
                        <div class="form-outline flex-fill">
                            <input type="text" id="form1" class="form-control form-control-lg" />
                            <label class="form-label" for="form1">Promotion code</label>
                        </div>
                        <button type="button" class="btn btn-outline-dark btn-lg ms-3">Apply</button>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body p-4 d-flex flex-row">
                        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                          <!-- display word subtotal -->
                            <h4 class="mb-0" type='text' value='sub' id='sub'> Subtotal:</h4>

                            <!-- adding all the items in the cart to get subtotal before chekout page -->
                            <?php $subtotal += (int) htmlspecialchars($item["price"], 0, false); ?>
                          <!-- display subtotal amount -->
                            <h5 class="mb-0" type='text' value='subtotal' id='subtotal'>
                                <?php htmlspecialchars($item["subtotal"]); ?>
                            </h5>

                        </div>
                        <div style="position:relative; left:100px; top:2px;">

                            <button type="button" class="btn btn-dark"
                                onclick="window.location.href='checkout.php'">Continue to Check Out</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>