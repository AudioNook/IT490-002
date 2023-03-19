<?php
// This is only for testing purposes


if (isset($_POST['submit'])) {
    // User agent and access token
    $user_agent = 'AudioNook/0.1 +https://github.com/AudioNook/IT490-002';
    $key = 'ZJheumfPznGeBujBlIso';
    $secret = 'uCPtHkhTHyVIRtKOaPthnhoPKkzxoAQs';

    $artist = $_POST['artist'];
    $release_title = '';
    $type = 'release';
    $format = 'vinyl';
    
    $per_page = 96;
    $page = 1;
    $url = "https://api.discogs.com/database/search?" .
         "q=". urlencode($artist) .
         "&format=" . urlencode($format) .
         "&type=" . urlencode($type) .
         "&per_page=" . urlencode($per_page) .
         "&page=" . urlencode($page);

    
    

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
    }

    // Close the cURL session
    curl_close($ch);

    
    //loops through the results and creates a $allGenres array to populated the filter dropdown.
    $allGenres = array();
    foreach ($results as $res)
    {
    foreach($res['genre'] as $gen) 
    { 
    if(!in_array($gen, $allGenres))
    {
      array_push($allGenres, $gen);
    }
  }
}
}
    

    

?>
<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <title>AudioNook Search</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>AudioNook Search</h1>
            <form method="POST" action="">
                <div class="mb-3">
                    
                    <input type="text" class="form-control" id="artist" name="artist" placeholder="Search..." required>  <button type="submit" name="submit" class="btn btn-info">Search</button>
                </div>
               
                <p id = "contact-btn" style = "float:right;"> <a href="#" class="btn btn-primary" onClick="toggleFields()">Details View</a> </p>
                <div class="container">
                    <div class="btn-group">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            data-bs-auto-close="true" aria-expanded="false">
                            Filter
                        </button>
                        <ul class="dropdown-menu">
                          <?php foreach($allGenres as $g)/*Populates the filter dropdown*/:?>
                            
                            <li><a class="dropdown-item" href="#"><?php echo($g);?></a></li>
                          <?php endforeach ?>  
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
  function toggleFields(){
    $('#details1, #details2').toggle("slow");
    }
</script>
<?php if (isset($results) && !empty($results)):?>
  <div class ="row">
    <?php foreach ($results as $result): ?>
      <div class="col-md-4 mv-4" style="padding: 15px;">
        <div class="card bg-light mb-3" style="width: 18rem;">
          <img class="card-img-top" src="<?php echo $result['cover_image']   ?>" alt="Album Covers" onerror="this.onerror=null;this.src='https://tinyurl.com/5n7fs4w8';" width = "300" height = "300" > 
            <div class="card-body" style = "max-height = 350px ">
              <h5 class="card-title" ><?php echo htmlspecialchars($result['title']?? "n/a"); ?></h5>
              <p id= "details1" class="card-text"><?php echo htmlspecialchars($result['year'] . " " ?? "n/a"); echo htmlspecialchars($result['country'] ?? "n/a"); echo '<br> <br>'; echo "Genre: <br>";  foreach($result['genre'] as $genre) {echo $genre, ' '; } ?></p>
              <p class="card-text" id= "details2"><?php echo "Formats: <br>"; foreach($result['format'] as $formats) {echo $formats, '  ';} echo '<br><br>';  ?> </p>
              <p style = " padding: 5px;"> <a href="#" class="btn btn-success" >Add to Collection</a> </p>
              
            </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
  </div>
</body>

</html>
<?php
require(__DIR__ . "/pagination.php");
?>