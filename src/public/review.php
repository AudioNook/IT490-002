<?php require(__DIR__ . "/../partials/nav.php"); 
logged_in(true);
?>
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