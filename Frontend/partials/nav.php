<?php
require_once(__DIR__ . "/../lib/functions.php");
require __DIR__ . "/../../vendor/autoload.php";
$checkSession = new DBRequests();
$checkSession->validateSession();
$is_logged_in = true;
if (basename($_SERVER['PHP_SELF']) !== 'login.php') { // check if the current page is not login.php
   $is_logged_in = logged_in();
}
?>

<head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
   <script src="<?php echo get_url('js/utilities.js'); ?>"></script>
   <style>
      body {
         padding-top: 80px;
         /* Adjust according to the height of your navbar */
      }
   </style>
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
   <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
         <i class="fas fa-bars"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
         <a class="navbar-brand mt-2 mt-lg-0" href="#">
            <img src="https://www.creativefabrica.com/wp-content/uploads/2019/02/Monogram-AN-Logo-Design-by-Greenlines-Studios.jpg" height="40" alt="AudioNook Logo" loading="lazy" />
         </a>
         <a class="navbar-brand" href="<?php echo get_url('landing.php'); ?>">AudioNook</a>
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <?php if (!$is_logged_in) : ?>
               <li class="nav-item">
                  <a class="nav-link" href="<?php echo get_url('login.php'); ?>">Login</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="<?php echo get_url('register.php'); ?>">Register</a>
               </li>
            <?php endif; ?>
            <?php if ($is_logged_in) : ?>
               <li class="nav-item">
                  <a class="nav-link" href="<?php echo get_url('start_collecting.php'); ?>">
                  Collection Search
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag-plus" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5z"/>
  <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
</svg>
                  </a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="<?php echo get_url('marketplace.php'); ?>">
                     Marketplace
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                        <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z" />
                     </svg>
                  </a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="<?php echo get_url('forums.php'); ?>">
                     Forums
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-left-text" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4.414A2 2 0 0 0 3 11.586l-2 2V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12.793a.5.5 0 0 0 .854.353l2.853-2.853A1 1 0 0 1 4.414 12H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                        <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z" />
                     </svg>
                  </a>
               </li>
            <?php endif; ?>
         </ul>
      </div>
      <?php if ($is_logged_in) : ?>
         <div class="d-flex align-items-center">
            <?php if (basename($_SERVER['PHP_SELF']) !== 'login.php') : ?>
               <?php require(__DIR__ . "/../lib/utils/cart_count.php"); ?>
               <li class="nav-item">
                  <a class="text-secondary text-decoration-none" href="<?php echo get_url('cart.php'); ?>">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                     </svg> <span id="cart-count">(<?php echo $cart_count ?>)</span>
                  </a>
               </li>

               <div class="dropdown">
                  <a class="text-secondary text-decoration-none me-3 dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                     </svg>
                  </a>
                  <!-- Placeholder for Notifications -->
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                     <li>
                        <a class="dropdown-item" href="#">Notification 1</a>
                     </li>
                     <li>
                        <a class="dropdown-item" href="#">Notification 2</a>
                     </li>
                     <li>
                        <a class="dropdown-item" href="#">Notification 3</a>
                     </li>
                  </ul>
               </div>
            <?php endif; ?>
            <?php if (basename($_SERVER['PHP_SELF']) !== 'login.php') : ?>
               <div class="dropdown">
                  <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                     <img src="https://via.placeholder.com/50" class="rounded-circle" height="25" alt="Profile Image" loading="lazy" />
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                     <li>
                        <a class="dropdown-item" href="<?php echo get_url('profile.php'); ?>">Profile</a>
                     </li>
                     <li>
                        <a class="dropdown-item" href="<?php echo get_url('logout.php'); ?>">Logout</a>
                     </li>
                  </ul>
               </div>
            <?php else : ?>
               <a class="text-secondary text-decoration-none" href="<?php echo get_url('register.php'); ?>">Register</a>
            <?php endif; ?>
         </div>

      <?php endif; ?>
   </div>
</nav>