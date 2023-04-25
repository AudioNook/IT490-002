<?php

/* This page will host the search if a user is signed in. */

require(__DIR__ . "/../partials/nav.php");

$format = "Vinyl";
$genres = [];
$search = "";

if (isset($_GET['searching'])) {
  $search = htmlspecialchars($_GET['searching']);
  $dmzRequest = new DMZRequests();
  $results = $dmzRequest->search($search, $format, $genres, 1);
  $results = $results['results'];
  $response_data = $results;
  $total_items = 0;
  $per_page = 18;
  if (isset($response_data['pagination']['items'])) {
    $total_items = $response_data['pagination']['items'];
    foreach ($results as $result) {
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
//var_dump($results);
//var_dump($genres);
?>
<!doctype html>
<html lang="en">

<head>
  <title>AudioNook Collecting</title>
  <link rel="stylesheet" href="<?php echo get_url('/css/collect.css'); ?>">
</head>

<body>
  <?php include(__DIR__ . "/../partials/collect.php"); ?>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>AudioNook Search</h1>
        <!-- Search Form -->
        <form class="form-group">
          <div class="row">
            <div class="col-md-9 mb-3">
              <div class="input-group">
                <input type="text" class="form-control" id="searching" name="searching" placeholder="Search..." value="<?php echo htmlspecialchars($search) ?>" required>
                <button type="submit" class="btn btn-info">Search</button>
                <select class="btn btn-secondary dropdown-toggle" type="button" name="format" id="format-select" data-bs-toggle="dropdown" aria-expanded="false">
                  <option class="dropdown-menu" aria-labelledby="format-select" disabled selected>Format</option>
                  <option class="dropdown-item" value="Vinyl" <?php echo $format == "Vinyl" ? 'selected' : ''; ?>>Vinyl</option>
                  <option class="dropdown-item" value="Cassette" <?php echo $format == "Cassette" ? 'selected' : ''; ?>>Cassette</option>
                  <option class="dropdown-item" value="CD" <?php echo $format == "CD" ? 'selected' : ''; ?>>CD</option>
                </select>
                <select class="btn btn-secondary dropdown-toggle" type="button" name="genre" id="genre-select" data-bs-toggle="dropdown" aria-expanded="false">
                  <option class="dropdown-menu" aria-labelledby="genre-select" disabled selected>Genre</option>
                  <?php if (!empty($genres)) : ?>
                    <?php foreach (array_unique($genres) as $g) : ?>
                      <option class="dropdown-item" value="<?php echo htmlspecialchars($g); ?>"><?php echo htmlspecialchars($g); ?></option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12"> RIP
              <button disabled class="btn btn-primary details-btn" id="contact-btn">☠ Details View</button>
            </div>
          </div>
        </form>
        <!-- End Search Form -->
      </div>
    </div>

    <?php if (isset($results) && !empty($results)) : ?>
      <div class="row">
        <?php $index = 0; ?>
        <?php foreach ($results as $result) : ?>
          <div class="col-md-4 mv-4" style="padding: 15px;">
            <div class="card bg-light mb-3" style="width: 18rem;">
              <img class="card-img-top" src="<?php echo !empty($result['cover_image']) ? $result['cover_image'] : 'https://tinyurl.com/5n7fs4w8'; ?>" alt="Album Covers" onerror="this.onerror=null;this.src='https://tinyurl.com/5n7fs4w8';" width="300" height="300">
              <div class="card-body" style="max-height: 350px ">
                <h5 class="card-title"><?php echo isset($result['title']) ? htmlspecialchars($result['title']) : "N/A"; ?></h5>

                <button class="btn details-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#more-details-<?php echo $index; ?>" aria-expanded="false" aria-controls="more-details-<?php echo $index; ?>">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-bar-expand" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M3.646 10.146a.5.5 0 0 1 .708 0L8 13.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zm0-4.292a.5.5 0 0 0 .708 0L8 2.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708zM1 8a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 0 1h-13A.5.5 0 0 1 1 8z" />
                  </svg>
                </button>

                <!-- Add unique identifier to the more details section -->
                <div class="collapse" id="more-details-<?php echo $index; ?>">
                  <p class="details1 card-text"><?php echo htmlspecialchars(($result['year'] ?? "N/A") . " " . ($result['country'] ?? "N/A")) . '<br><br>' . "Genre: <br>" . (isset($result['genre']) ? implode(", ", $result['genre']) : "N/A"); ?></p>
                  <p class="details2 card-text"><?php echo "Formats: <br>" . (isset($result['format']) ? implode(", ", $result['format']) : "N/A") . '<br><br>'; ?></p>
                </div>

                <form onsubmit="add_items(this, event)">
                  <input type="hidden" name="release_id" value="<?php echo intval($result['id'] ?? 0) ?>" />
                  <input type="hidden" name="title" value="<?php echo is_string($result['title']) ? htmlspecialchars($result['title']) : "N/A"; ?>" />
                  <input type="hidden" name="cover_image" value="<?php echo !empty($result['cover_image']) ? $result['cover_image'] : "N/A" ?>" />
                  <?php $genre_strings;
                  if (isset($result['genre']) && is_array($result['genre'])) {
                    $genre_arr = array_map(function ($arr) {
                      return htmlspecialchars($arr);
                    }, $result['genre']);
                    $genre_strings = implode(", ", $genre_arr);
                  } else {
                    $genre_strings = "N/A";
                  } ?>
                  <input type="hidden" name="genres" value="<?php echo $genre_strings ?>" />
                  <input type="hidden" name="format" value="<?php echo isset($result['format']) ? htmlspecialchars(implode(", ", $result['format'])) : "N/A"; ?>" />
                  <input type="hidden" name="action" value="add" />
                  <button type="submit" value="Add to Collection" class="btn btn-outline-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-check" viewBox="0 0 16 16">
                      <path fill-rule="evenodd" d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                      <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                    </svg>
                    Add To Collection
                  </button>
                </form>
              </div>
            </div>
          </div>
          <?php $index++; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- Paginated Pages! --->

  <?php
  if (isset($response_data['pagination']['items'])) {
    $total_items = $response_data['pagination']['items'];
    $pagination = paginate($total_items, $per_page);

    include(__DIR__ . "/../partials/pagination.php");
  }
  ?>

  <!-- Script to handle genres input and details view toggle -->

  <script>
    $(document).ready(function() {
      load_collected_items();
      $('.card .details1, .card .details2').hide();

      $('#genre-select').on('change', function() {
        var genre = $(this).val();
        var currentUrl = window.location.href;
        var url = new URL(currentUrl);

        // Update the 'genre' parameter in the URL
        url.searchParams.set('genre', genre);

        // Redirect to the new URL with the updated 'genre' parameter
        window.location.href = url.toString();
      });
      $('#format-select').on('change', function() {
        var format = $(this).val();
        var currentUrl = window.location.href;
        var url = new URL(currentUrl);

        // Update the 'format' parameter in the URL
        url.searchParams.set('format', format);

        // Redirect to the new URL with the updated 'format' parameter
        window.location.href = url.toString();
      });
    });
  </script>
</body>

</html>