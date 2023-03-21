<?php

/* This page will host the search if a user is signed in. */
require(__DIR__ . "/../partials/nav.php");


if (isset($_POST['submit'])) {
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
  if (isset($_POST['artist'])) {
    $query_params['q'] = $_POST['artist'];
  }

  //process filters/sorting

  // Add format if set in GET data
  if (isset($_GET['format'])) {
    $format = htmlspecialchars($_GET['format']);
    if (in_array($format, ["Vinyl", "Cassette", "CD"])) {
      $query_params['format'] = $format;
    }
  }

  // Add genre if set in GET data
  if (isset($_GET['genre'])) {
    $genre = htmlspecialchars($_GET['genre']);
    $query_params['genre'] = $genre;
  }


  $query_params['per_page'] = 18;
  $page = (int)htmlspecialchars($_GET['page'] ?? 1, ENT_QUOTES, 'UTF-8');
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
    $genres = [];
    foreach ($response_data['results'] as $result) {
      if (isset($result['genre']) && is_string($result['genre'])) {
        $genres = array_merge($genres, explode(',', $result['genre']));
      }
    }
    $per_page = 18;
    $genres = array_unique($genres);
    $pagination = paginate($response_data['pagination']['items'], $per_page);
  }

  // Close the cURL session
  curl_close($ch);
}

?>
<!doctype html>
<html lang="en">

<head>
  <title>AudioNook Search</title>
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>AudioNook Search</h1>
        <form method="POST" action="">
          <div class="mb-3">

            <input type="text" class="form-control" id="artist" name="artist" placeholder="Search..." required> <button type="submit" name="submit" class="btn btn-info">Search</button>
          </div>

          <p id="contact-btn" style="float:right;"> <a href="#" class="btn btn-primary" onClick="toggleFields()">Details View</a> </p>
          <div class="container">
            <!-- Filter dropdown -->
            <div class="btn-group">
              <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                Filter
              </button>
              <ul class="dropdown-menu">
                <li><strong>Genres</strong></li>
                <?php foreach ($genres as $genre) : ?>
                  <li><a href="?genre=<?php echo urlencode($genre); ?>" class="dropdown-item"><?php echo htmlspecialchars($genre); ?></a></li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </form>
      </div>
    </div>
    <script>
      $(function() {
        $('#details1, #details2')
          .hide();
      });

      function toggleFields() {
        $('#details1, #details2').toggle("slow");
      }
    </script>
    <?php if (isset($results) && !empty($results)) : ?>
      <div class="row">
        <?php foreach ($results as $result) : ?>
          <div class="col-md-4 mv-4" style="padding: 15px;">
            <div class="card bg-light mb-3" style="width: 18rem;">
              <img class="card-img-top" src="<?php echo $result['cover_image']   ?>" alt="Album Covers" onerror="this.onerror=null;this.src='https://tinyurl.com/5n7fs4w8';" width="300" height="300">
              <div class="card-body" style="max-height: 350px ">
                <h5 class="card-title"><?php echo htmlspecialchars($result['title'] ?? "N/A"); ?></h5>
                <p id="details1" class="card-text"><?php echo htmlspecialchars(($result['year'] ?? "N/A") . " " . ($result['country'] ?? "N/A")) . '<br><br>' . "Genre: <br>" . (isset($result['genre']) ? implode(", ", $result['genre']) : "N/A"); ?></p>
                <p class="card-text" id="details2"><?php echo "Formats: <br>" . (isset($result['format']) ? implode(", ", $result['format']) : "N/A") . '<br><br>'; ?></p>
                <p style="padding: 5px;"> <a href="#" class="btn btn-success">Add to Collection</a> </p>

              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <?php
// Get total number of items and generate pagination links
if (isset($response_data['pagination']['items'])) {
  $total_items = $response_data['pagination']['items'];
  $pagination = paginate($total_items, $per_page);

  // Output pagination links
  include(__DIR__ . "/../partials/pagination.php");
}
?>
</body>

</html>