<?php require(__DIR__ . "/../partials/nav.php"); 
   logged_in(true);
   $rbMQCOL = rbmqc_db();
   
   $reviews_req = array();
   $reviews_req['type'] = 'get_reviews';
   $reviews_req['message'] = "Sending reviews request";
   $reviews;

   $response = json_decode($rbMQCOL->send_request($reviews_req), true);
   $rbMQCOL->close();

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
      $product_id = $_GET['product_id'];
      $comment = $_GET['comment'];
      $new_reviews_req = array();
      $new_reviews_req['type'] = 'new_review';
      $new_reviews_req['message'] = "Sending new reviews request";
      $new_reviews_req['product_id'] = $product_id;
      $new_reviews_req['comment'] = $comment;
      $rbMQc = rbmqc_db();
      $created_new_reviews = json_decode($rbMQc->send_request($new_reviews_req), true);
      $rbMQc->close();
      error_log("hello?");
        if ($created_new_reviews['type'] == 'new_review') {
            switch ($created_new_reviews['code']) {
                case 200:
                    error_log("Sucessfully uploaded new review");
                    echo '<script language="javascript">';
                    echo 'alert("' . $response['message'] . '")';
                    echo '</script>';
                    redirect(get_url("review.php?id=".$product_id));
                    break;
                case 401:
                    echo '<script language="javascript">';
                    echo 'alert("' . $response['message'] . '")';
                    echo '</script>';
                    break;
                default:
                    error_log($response['message']);
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
         <form class="form" method="POST">
            <label for="product_id">Product ID:</label><br>
            <input type="text" id="product_id" name="product_id" value=""><br>
            <label for="comment">Write a review:</label><br>
            <input type="comment" id="comment" name="comment" value=""><br>
            <button type="submit" class="btn btn-primary btn-block mb-4">
                Post Review
            </button>
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
   #product_id {
    width: 200px; /* adjust the value to your desired width */
}

#comment {
    height: 150px; /* adjust the value to your desired height */
}
</style>
<?php
   include('footer.php');
   ?>