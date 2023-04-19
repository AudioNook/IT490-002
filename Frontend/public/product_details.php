<?php require(__DIR__ . "/../partials/nav.php");

$product_id = $_GET['id'];
if(is_null($product_id) > 0 || $product_id < 0){
	// message
	redirect("/marketplace.php");
}
$itemDetails = new DBRequests();
$item = $itemDetails->getItemDetails($product_id)["marketplace_item"];
?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-5">
            <div class="main-img">
                <img class="img-fluid" src="<?php echo $item['cover_image'] ?>" alt="ProductS">
            </div>
            <br>
            <div class="d-flex justify-content-center">
                <div class="embed-responsive embed-responsive-21by9" style="width: 100%; max-width: 560px;">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/NMRhx71bGo4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>

            <div class="container py-5">
                <div class="card text-black border-dark" style="border-radius: 10px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" alt="Generic placeholder image" class="rounded-circle border border-dark border-1" style="width: 70px;">
                            <div class="ms-3">
                                <p class="mb-0">@<?php echo $item['username'] ?></p>
                                <div>
                                    <button type="button" class="btn btn-outline-dark btn-sm" data-mdb-ripple-color="dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                                        </svg>
                                        View Profile
                                    </button>
                                    <button type="button" class="btn btn-outline-dark btn-sm" data-mdb-ripple-color="dark">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left" viewBox="0 0 16 16">
                                            <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                        </svg>
                                        Message
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-7">
            <div class="main-description px-2">
                <div class="category text-bold">
                    <?php echo "Condition: " . $item['item_condition'] ?>
                </div>
                <h3 class="product-title text-bold my-3">
                    <?php echo $item['title']; ?>
                </h3>
                <div class="price-area my-4">
                    <p class="new-price text-bold mb-1"><strong> <?php echo "Format: " . $item['format'] ?> </strong></p>
                    <p class="new-price text-bold mb-1"> <?php echo "Price: " . $item['price'] ?></p>
                    <p class="text-secondary mb-1"><?php echo "Genres: " . $item['genres'] ?></p>
                </div>

                <div class="buttons d-flex align-items-center my-5">

                    <form id="add-to-cart-form" class="d-flex ms-2" method="POST">
                        <button disabled href="#" class="btn btn-outline-primary text-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmark" viewBox="0 0 16 16">
                                <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z" />
                            </svg>
                            Wishlist</button>
                        <input type="hidden" name="product_id" value="<?php echo $products['id']; ?>" />
                        <input type="hidden" name="user_id" value="<?php echo get_user_id(); ?>" />
                        <input type="hidden" name="action" value="add" />
                        <button type="submit" class="btn btn-outline-dark text-nowrap">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                            </svg>
                            Add to Cart
                        </button>
                        <input type="number" readonly class="form-control small-input ms-2" id="disabledTextInput" name="desired_quantity" value="<?php echo 1; ?>" min="0" max="<?php echo 1; ?>" />
                    </form>
                </div>
            </div>

            <div class="product-details my-4">
                <h4 class="details-title text-color mb-1">Description</h4>
                <p class="description"> <?php echo $item['item_description'] ?> </p>
            </div>

            <div class="row questions bg-light p-3">
                <div class="col-md-1 icon">
                    <i class="fa-brands fa-rocketchat questions-icon"></i>
                </div>
                <div class="col-md-11 text">
                    <h4 class="details-title text-color mb-1">Song List</h4>
                    <p>1. Something - by something Feat something </p>
                    <p>2. Something - by something Feat something </p>
                    <p>3. Something - by something Feat something </p>
                    <p>4. Something - by something Feat something </p>
                    <p>5. Something - by something Feat something </p>
                </div>
            </div>

            <div class="delivery my-4">
                <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-truck"></i></span> <b>Delivery done in 3 days from date of purchase</b> </p>
                <p class="text-secondary">Order now to get this product delivery</p>
            </div>
            <div class="delivery-options my-4">
                <p class="font-weight-bold mb-0"><span><i class="fa-solid fa-filter"></i></span> <b>Delivery options</b> </p>
                <p class="text-secondary">View delivery options here</p>
            </div>


        </div>
    </div>
</div>

<div class="container similar-products my-4">
    <hr>
    <p class="display-5">Album Reviews</p>
    <!-- Seller Reviews -->
</div>
<div class="container similar-products my-4">
    <hr>
    <p class="display-5">Similar Products</p>
    <!-- Seller Reviews -->
</div>