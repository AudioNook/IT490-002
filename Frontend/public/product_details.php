<?php require(__DIR__ . "/../partials/nav.php");
?>
<div class="container my-5">
    <div class="row">
        <div class="col-md-5">
            <div class="main-img">
                <img class="img-fluid" src="https://i.discogs.com/EB6i3MltuGKX7DuCF4TDeqxK-ReTBPAf2cInVw5kX0c/rs:fit/g:sm/q:90/h:600/w:600/czM6Ly9kaXNjb2dz/LWRhdGFiYXNlLWlt/YWdlcy9SLTcyNjg5/NDMtMTQzNzYzNTcy/Ni00NzU5LmpwZWc.jpeg" alt="ProductS">
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

                <div class="buttons d-flex my-5">
                    <div class="block">
                        <a href="#" class="btn btn-primary">Wishlist</a>
                    </div>
                    <div class="block">
                        <button class="btn btn-success">Add to cart</button>
                    </div>

                    <div class="block quantity">
                        <input type="number" class="form-control" id="cart_quantity" value="1" min="0" max="5" placeholder="Enter email" name="cart_quantity">
                    </div>
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