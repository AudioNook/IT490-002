<?php require(__DIR__ . "/../partials/nav.php");

$product_id = $_GET['id'];

?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-5">
            <div class="main-img">
                <img class="img-fluid" src="https://i.discogs.com/EB6i3MltuGKX7DuCF4TDeqxK-ReTBPAf2cInVw5kX0c/rs:fit/g:sm/q:90/h:600/w:600/czM6Ly9kaXNjb2dz/LWRhdGFiYXNlLWlt/YWdlcy9SLTcyNjg5/NDMtMTQzNzYzNTcy/Ni00NzU5LmpwZWc.jpeg" alt="ProductS">
            </div>
            <br>
            <div class="d-flex justify-content-center">
                <div class="embed-responsive embed-responsive-21by9" style="width: 100%; max-width: 560px;">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/NMRhx71bGo4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="main-description px-2">
                <div class="category text-bold">
                    <?php echo "Format: [Format]" ?>
                </div>
                <h3 class="product-title text-bold my-3">
                    <?php echo "Product Title"; ?>
                </h3>
                <div class="price-area my-4">
                    <p class="new-price text-bold mb-1"> Price: $80</p>
                    <p class="text-secondary mb-1"><?php echo "Genres: [Genres]" ?></p>
                </div>

                <div class="buttons d-flex align-items-center my-5">

                    <form id="add-to-cart-form" class="d-flex ms-2" method="POST">
                        <a disabled href="#" class="btn btn-outline-primary">Wishlist</a>
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
                <p class="description">Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat excepturi odio recusandae aliquid ad impedit autem commodi earum voluptatem laboriosam? </p>
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