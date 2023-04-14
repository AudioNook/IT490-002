<?php

require(__DIR__ . "/../partials/nav.php");

$format = "Vinyl";
$genres = [];
$search = "";
if (isset($_GET['searching'])) {
    $search = htmlspecialchars($_GET['searching']);
    // User agent and access token
    $user_agent = 'AudioNook/0.1 +https://github.com/AudioNook/IT490-002';
    $key = 'ZJheumfPznGeBujBlIso';
    $secret = 'uCPtHkhTHyVIRtKOaPthnhoPKkzxoAQs';

    $results = [[]];

    // Initialize URL with base search endpoint
    $base_url = "https://api.discogs.com/database/search";

    // Initialize query parameters array
    $query_params = [];

    // Add search term if set in POST data
    if (isset($_GET['searching'])) {
        $query_params['q'] = $search;
    }
    $search = $_GET['searching'];
    //process filters/sorting

    // Add format if set in GET data
    if (isset($_GET['format'])) {
        $format = htmlspecialchars($_GET['format']);
        if (in_array($format, ["Vinyl", "Cassette", "CD"])) {
            $query_params['format'] = $format;
        }
    } else {
        $query_params['format'] = $format;
    }

    // Add genre if set in GET data
    if (isset($_GET['genre']) && !empty($_GET['genre']) && !is_null($_GET['genre'])) {
        $genre = htmlspecialchars($_GET['genre']);
        $query_params['genre'] = $genre;
    }
    $query_params['type'] = 'master';

    $query_params['per_page'] = 12;
    $page = (int) htmlspecialchars($_GET['page'] ?? 1, ENT_QUOTES, 'UTF-8');
    $query_params['page'] = $page;


    // Combine base URL and query parameters using http_build_query()
    $url = $base_url . '?' . http_build_query($query_params);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "User-Agent: $user_agent",
        "Authorization: Discogs key=$key, secret=$secret",
    ]);

    // Add the CA certificate bundle for SSL verification
    curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/certs/cacert.pem');
    $response = curl_exec($ch);
    $response_data = json_decode($response, true);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        $results = $response_data['results'];
        $total_items = 0;
        $per_page = 18;
        if (isset($response_data['pagination']['items'])) {
            $total_items = $response_data['pagination']['items'];
            $pagination = paginate($total_items, $per_page);
            foreach ($response_data['results'] as $result) {
                if (isset($result['genre']) && is_array($result['genre'])) {
                    $genres = array_merge($genres, $result['genre']);
                }
            }
            if (!empty($genres)) {
                $genres = array_unique($genres);
                sort($genres);
            }
        }
    }
    // Close the cURL session
    curl_close($ch);
}



// $cart = array(
//     array(
//         'product_id' => 1,
//         'name' => 'Cassette',
//         'price' => 0,
//         'quantity' => 1,
//     ),
//     array(
//         'product_id' => 2,
//         'name' => 'CD',
//         'price' => 10.99,
//         'quantity' => 2,
//     ),
//     array(
//         'product_id' => 3,
//         'name' => 'Vinyl',
//         'price' => 15.99,
//         'quantity' => 1,
//     )

// );
// $total = 0;
// $subtotal = 0;


?>
<!doctype html>
<html lang="en">

<head>
    <title>AudioNook Collecting</title>
    <link rel="stylesheet" href="<?php echo get_url('/css/collect.css'); ?>">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>AudioNook Search</h1>
                <!-- Search Form -->
                <form class="form-group">
                    <div class="row">
                        <div class="col-md-9 mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="searching" name="searching"
                                    placeholder="Search..." value="<?php echo htmlspecialchars($search) ?>" required>
                                <button type="submit" class="btn btn-info">Search</button>
                                <select class="btn btn-secondary dropdown-toggle" type="button" name="format"
                                    id="format-select" data-bs-toggle="dropdown" aria-expanded="false">
                                    <option class="dropdown-menu" aria-labelledby="format-select" disabled selected>
                                        Format</option>
                                    <option class="dropdown-item" value="Vinyl" <?php echo $format == "Vinyl" ? 'selected' : ''; ?>>Vinyl</option>
                                    <option class="dropdown-item" value="Cassette" <?php echo $format == "Cassette" ? 'selected' : ''; ?>>Cassette</option>
                                    <option class="dropdown-item" value="CD" <?php echo $format == "CD" ? 'selected' : ''; ?>>CD</option>
                                </select>
                                <select class="btn btn-secondary dropdown-toggle" type="button" name="genre"
                                    id="genre-select" data-bs-toggle="dropdown" aria-expanded="false">
                                    <option class="dropdown-menu" aria-labelledby="genre-select" disabled selected>Genre
                                    </option>
                                    <?php if (!empty($genres)): ?>
                                        <?php foreach (array_unique($genres) as $g): ?>
                                            <option class="dropdown-item" value="<?php echo htmlspecialchars($g); ?>"><?php echo htmlspecialchars($g); ?></option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a href="#" class="btn btn-primary" id="contact-btn">Details View</a>
                        </div>
                    </div>
                </form>
                <!-- End Search Form -->
            </div>
        </div>
        <?php include(__DIR__ . "/../partials/collect.php"); ?>




        <section class="h-100" style="background-color: #eee;">
            <div class="container h-100 py-5">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-10">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-normal mb-0 text-black">Shopping Cart</h3>
                            <div>
                                <p class="mb-0"><span class="text-muted">Sort by:</span> <a href="#!"
                                        class="text-body">price <i class="fas fa-angle-down mt-1"></i></a></p>
                            </div>
                        </div>
                        <div class="card rounded-3 mb-4">

                            <!-- start for each -->
                            <?php $total = 0; ?>

                            <div class="card-body p-4">
                                <?php if (isset($results) && !empty($results)): ?>
                                    <?php foreach ($cart as $item): ?>
                                        <div class="row">

                                            <div class="card">
                                                <div class="row d-flex justify-content-between align-items-center">
                                                    <div class="col-md-2 col-lg-2 col-xl-2">
                                                        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-shopping-carts/img1.webp"
                                                            class="img-fluid rounded-3" alt="Cotton T-shirt">
                                                    </div>
                                                    <div class="col-md-3 col-lg-3 col-xl-3">
                                                        <!-- Replace Pname with actual product name-->
                                                        <p class="lead fw-normal mb-2">Product:
                                           
                                                        <?php echo htmlspecialchars($item["name"]); ?>
                                                        </p>
                                                        <!-- end product name -->
                                                        <span class="text-muted">Product ID:</span>
                                                        <?php echo htmlspecialchars($item["product_id"]); ?>
                                                        </p>
                                                    </div>
                                                    <div class="col-md-3 col-lg-3 col-xl-2 d-flex">
                                                        <button class="btn btn-link px-2"
                                                            onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <!-- if number is less than 1, it is deleted from the cart -->
                                                        <input id="form1" min="0" max="1" name="quantity" value="1"
                                                            type="number" class="form-control form-control-sm" />

                                                        <button class="btn btn-link px-2"
                                                            onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                    <!-- display price -->
                                                    <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1">
                                                        <p><span class="text-muted">Price: </span>
                                                            <?php echo htmlspecialchars($item["price"]); ?>
                                                    </div>
                                                    <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                                        <a href="#!" class="btn btn-danger"></i></a>
                                                    </div>
                                                    <!-- adding all the items in the cart to get subtotal before chekout page -->
                                                    <?php $subtotal += (double) htmlspecialchars($item["price"], 0, false); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                        <!-- maybe extra div -->

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
                                    <h5 class="mb-0" type='text' value='subtotal' id='subtotal'>
                                        <?php echo htmlspecialchars($subtotal); ?>
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
