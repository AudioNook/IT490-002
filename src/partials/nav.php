<?php
   require_once(__DIR__ . "/../lib/functions.php");
   //check_jwt($rbMQc);
   $is_logged_in = false;
   if (basename($_SERVER['PHP_SELF']) !== 'login.php') { // check if the current page is not login.php
     $is_logged_in = logged_in();
   }
   ?>
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
   <script src ="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"></script>
   <script src="<?php //echo get_url('/js/validateJWT.js'); ?>"></script>
</head>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
   <img src="https://www.creativefabrica.com/wp-content/uploads/2019/02/Monogram-AN-Logo-Design-by-Greenlines-Studios.jpg" width="50" height="40" alt="">
   <a class="navbar-brand" >  </a>
   <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
   <span class="navbar-toggler-icon"></span>
   </button>
   <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
         <?php if (!$is_logged_in) : ?>
         <a class="navbar-brand" href="<?php echo get_url('landing.php'); ?>">AudioNook</a>
         <li class="nav-item">
            <a class="nav-link" href="<?php echo get_url('login.php'); ?>">Login</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="<?php echo get_url('register.php'); ?>">Register</a>
         </li>
         <?php endif; ?>
         <?php if ($is_logged_in) : ?>
         <a class="navbar-brand" href="#">AudioNook</a>
         <li class="nav-item">
            <a class="nav-link" href="<?php echo get_url('marketplace.php'); ?>">Marketplace</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="<?php echo get_url('profile.php'); ?>">Profile</a>
         </li>
         <li class="nav-item">
            <a class="nav-link" href="<?php echo get_url('forums.php'); ?>">Forum
            <a>
            </li>
         <li class="nav-item">
            <a class="nav-link" href="<?php echo get_url('review.php'); ?>">Review
            <a>
         </li>
         <li class="nav-item">
         <a class="nav-link" href="<?php echo get_url('logout.php'); ?>">Logout</a>
         </li>
         <?php endif; ?>
      </ul>
   </div>
</nav>