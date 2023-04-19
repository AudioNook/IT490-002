<?php require(__DIR__ . "/../partials/nav.php");

$product_id = $_GET['id'];
$user_id = get_user_id();
if (is_null($product_id) > 0 || $product_id < 0) {
    // message
    redirect("/marketplace.php");
}
$itemDetails = new DBRequests();
$item = $itemDetails->getItemDetails($product_id)["marketplace_item"];

$reviewsRequest = new DBRequests();
$reviewResponse = $reviewsRequest->getAlbumReviews($item['collection_item_id']);
if ($reviewResponse['code'] == 200) {
    $reviews = $reviewResponse['reviews'];
    $averageRating = 0;
    $reviewsAmt = count($reviews);
    foreach ($reviews as $review) {
        $averageRating += $review['rating'];
    }
    $averageRating = $averageRating / $reviewsAmt;
} else {
    $reviews = [];
    $averageRating = 0;
    $reviewsAmt = 0;
}
if (isset($_POST["submitreview"])) {
    $review = $_POST["review"];
    $rating = (int) $_POST["rating"];
    $collection_id = $item['collection_item_id'];
    $hasError = false;
    if ($rating < 1) {
        $hasError = true;
    }
    if (strlen($review) <= 0) {
        $hasError = true;
    }
    if (!isset($_GET["id"]) || is_null($product_id) > 0 || $product_id < 0) {
        $hasError = true;
    }
    // TODO maybe check if user has the album in their collection
    if (!$hasError) {
        $reviewAlbum = new DBRequests();
        $reviewAlbum->reviewAlbum($user_id, $collection_id, $review, $rating);
        redirect("/product_details.php?id=" . $product_id);
    }
    $reviewAlbum = new DBRequests();
    $reviewAlbum->reviewAlbum($user_id, $collection_id, $review, $rating);
    redirect("/product_details.php?id=" . $product_id);
}
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
?>

<head>
    <title><?php echo $item['title'] ?></title>
</head>
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
                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
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

    <div class="container py-5">
        <div class="card bg-light">
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <div class="rating-box fluid bg-dark text-white p-3 rounded aspect-ratio-1x1 w-50 d-flex flex-column align-items-center justify-content-center">
                            <h1 class="pt-4"><?php echo $averageRating; ?></h1>
                            <p class="">out of 5</p>
                            <div>
                                <?php for ($i = 0; $i < 5; $i++) {
                                    echo renderStar($i, $averageRating);
                                } ?>
                            </div>
                            <div><?php echo $reviewsAmt; ?> Reviews</div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h4 class="text-center mb-4">Review and Rate this Album!</h4>
                        <form class="fluid" method="POST">
                            <div class="form-outline mb-4 d-flex justify-content-center">
                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                    <input type="radio" class="btn-check" name="rating" id="<?php echo "Option" . $i + 1 ?>" value="<?php echo $i + 1 ?>" autocomplete="off">
                                    <label class="btn btn-outline-dark btn-sm" for="<?php echo "Option" . $i + 1 ?>"><?php echo getEmptyStar(); ?></label>
                                <?php endfor; ?>
                            </div>
                            <div class="form-outline mb-4">
                                <textarea class="form-control" placeholder="What'd you think?" name="review" rows="4"></textarea>
                                <!-- Submit and Clear button -->
                                <button type="reset" class="btn btn-outline-secondary btn-block mb-4">Clear</button>
                                <button type="submit" name="submitreview" class="btn btn-outline-dark btn-block mb-4">
                                    Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (count($reviews) > 0) : ?>
        <?php foreach ($reviews as $review) : ?>
            <div class="container py-5">
                <div class="d-flex align-items-start">
                    <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" alt="Generic placeholder image" class="rounded-circle border border-dark border-1" style="width: 70px;">
                    <div class="ms-3">
                        <h3 class="mt-2 mb-0">@<?php echo $review['username'] ?></h3>
                        <p class="text-left"><span class="text-muted"><?php echo getFullStar() . "(" . (int)$review['rating'] . ")" ?></span></p>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="review-description">
                        <p><?php echo $review['review'] ?></p>
                    </div>
                    <span class="publish py-3 d-inline-block w-100">Published <?php echo $review['created'] ?></span>
                    <button type="button" class="btn btn-outline-dark btn-sm" data-mdb-ripple-color="dark">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                        </svg>
                        View Profile
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <h2>No Reviews</h2>
    <?php endif; ?>
</div>
<div class="container similar-products my-4">
    <hr>
    <p class="display-5">Similar Products</p>
    <!-- Seller Reviews -->
</div>