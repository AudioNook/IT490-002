<?php require(__DIR__ . "/../partials/nav.php"); 
   logged_in(true);
   
   $reviews_req = array();
   $reviews_req['type'] = 'reviews';
   $reviews_req['message'] = "Sending reviews request";
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
   if (isset($_POST['product_id']) && isset($_POST['comment'])) {
      $bad_msg = "You must be logged in to this!!";
      if (!logged_in()) {
          echo '<script language="javascript">';
          echo 'alert("' . $bad_msg . '")';
          echo '</script>';
          redirect(get_url("login.php"));
      }
   }
   $hasError = false;
   if (!$hasError && isset($_GET['product_id'])) {

      $user_id = get_user_id();

      $reviews_req = array();
      $reviews_req['type'] = 'review';
      $reviews_req['product_id'] = $product_id;
      $reviews_req['comment'] = $comment;
      $reviews_req['user_name'] = $user_name;
      $reviews_req['created'] = $created;
      $reviews_req['user_id'] = $user_id;

      $created_review = json_decode($rbMQc->send_request($reviews_req), true);
      error_log("REEEEEEEE");
      if ($created_review['type'] == 'reviews') {
          switch ($created_review['code']) {
              case 200:
                  error_log("Sucessfully uploaded review");
                  echo '<script language="javascript">';
                  echo 'alert("' . $created_review['message'] . '")';
                  echo '</script>';
                  //redirect(get_url("posts.php?id=".$topic_id));
                  break;
              case 401:
                  echo '<script language="javascript">';
                  echo 'alert("' . $created_review['message'] . '")';
                  echo '</script>';
                  break;
              default:
                  error_log($created_review['message']);
          }
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
               <div class="card-header d-flex justify-content-between">
                  <div>Product ID: <?php echo $review['product_id']; ?></div>
                  <div>Product Name: <?php echo $review['productname']; ?></div>
               </div>
               <div class="card-body">
                  <p class="card-text">Username: <?php echo $review['user_name']; ?></p>
               </div>
               <div class="card-body">
                  <p class="card-text">User ID: <?php echo $review['user_id']; ?></p>
               </div>
               <div class="card-body">
                  <p class="card-text">Comment: <?php echo $review['comment']; ?></p>
               </div>
               <div class="card-body">
                  <p class="card-text">Created: <?php echo $review['created']; ?></p>
               </div>
            </div>
         </div>
      </div>
      <?php endforeach; ?>
      <?php else : ?>
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
         <form action="/action_page.php">
            <label for="fname">Product ID:</label><br>
            <input type="text" id="fname" name="fname" value=""><br>
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