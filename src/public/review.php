<?php require(__DIR__ . "/../partials/nav.php"); 
logged_in(true);

$reviews_req = array();
$reviews_req['type'] = 'reviews';
$reviews_req['message'] = "Sending discussion reviews request";
$reviews;
$response = json_decode($rbMQc->send_request($reviews_req), true);

if ($response['type'] == 'reviews') {
  switch ($response['code']) {
    case 200:
      $reviews = $response['reviews'];
      break;
    case 401:
      echo '<script language="javascript">';
      echo 'alert("' . $response['message'] . '")';
      echo '</script>';
      break;
    default:
      echo ($response['message']);
  }
}
?>



<html>

<head>
  <script>
    //validateJWT();
  </script>
  <title>Reviews</title>
</head>
<div class="container-fluid">

</div>
<div class="container-fluid">
  <?php if (!empty($reviews)) : ?>
    <?php foreach ($reviews as $review) : ?>
      <div class="container-fluid">
        <div>
          <div class="card m-3">
            <h5 class="card-header"> Product ID: <?php echo $review['product_id']; ?></h5>
            <div class="card-body">
               <p class="card-text">Comment: <?php echo $review['comment']; ?></p>
            </div>
            <div class="card-body">
              <p class="card-text">Created: <?php echo $review['created']; ?></p>
              </div>
              </div>
              
      <!--
               </div>
            <p class="card-text">Product Name: 
               <?//php echo $review['product_name']; ?></p>
          </div>
    -->
        </div>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <div>
      <div class="card m-3">
        <h5 class="card-header">Unable to Load Reviews!</h5>
        <div class="card-body">
          <p class="card-text">Try again next semester.</p>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div> 
</html>


<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Reviews</title>
   </head>
   <body>
      <div class="container">
         <h1>Review a Product!</h1>
         <form>
            <label for="search">Product Search:</label>
            <input type="text" id="search" name="search">
            <input type="submit" value="Search">
         </form>
         <form>
            <label for="review">Write a review:</label>
            <input type="text" id="review" name="review" style="height: 150px;">
            <input type="submit" value="Review">
         </form>
      </div>
   </body>
</html>
<style>
   .container {
   max-width: 600px;
   margin: 0 auto;
   padding: 20px;
   box-sizing: border-box;
   }
   input[type="text"] {
   display: block;
   margin-bottom: 10px;
   padding: 10px;
   width: 100%;
   box-sizing: border-box;
   border: 1px solid #ccc;
   border-radius: 5px;
   }
   input[type="submit"] {
   display: block;
   margin-top: 10px;
   padding: 10px;
   width: 100%;
   background-color: #008CBA;
   color: #fff;
   border: none;
   border-radius: 5px;
   cursor: pointer;
   }
</style>
<?php
   include('footer.php');
   ?>