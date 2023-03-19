<?php
   require(__DIR__ . "/../partials/nav.php");
   
   // Check if user is logged in
   
   logged_in(true);
   
   // User is logged in, show the forum page
   ?>
<html>
   <head>
      <script>
         //validateJWT();
      </script>
      <title>My Profile</title>
   </head>
   <body>
      <section style="background-color: #eee;">
         <div class="container py-5">
            <div class="row">
               <div class="col">
                  <nav aria-label="breadcrumb" class="bg-light rounded-3 p-3 mb-4">
                     <h5 class="my-3">My Profile</h5>
                  </nav>
               </div>
            </div>
            <div class="col-lg-8">
               <div class="card mb-4">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Username</p>
                        </div>
                        <div class="col-sm-9">
                           <p class="text-muted mb-0">$username</p>
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-sm-3">
                           <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                           <p class="text-muted mb-0">$email</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         </div>
         </div>
      </section>
   </body>
</html>
<div class="container">
  <div class="card">
    <div class="card-body text-center">
      <h5 class="my-3">My Collection</h5>
    </div>
  </div>
</div>
      <!-- Collection-->
      <section class="py-5">
         <div class="container px-4 px-lg-5 mt-5">
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
               <div class="col mb-5">
                  <div class="card h-100">
                     <!-- Product image-->
                     <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                     <!-- Product details-->
                     <div class="card-body p-4">
                        <div class="text-center">
                           <!-- Product name-->
                           <h5 class="fw-bolder">Product</h5>
                        </div>
                     </div>
                     <!-- Product actions-->
                     <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Remove from collection</a></div>
                     </div>
                  </div>
               </div>
               <div class="col mb-5">
                  <div class="card h-100">
                     <!-- Product image-->
                     <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                     <!-- Product details-->
                     <div class="card-body p-4">
                        <div class="text-center">
                           <!-- Product name-->
                           <h5 class="fw-bolder">Product</h5>
                        </div>
                     </div>
                     <!-- Product actions-->
                     <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Remove from collection</a></div>
                     </div>
                  </div>
               </div>
               <div class="col mb-5">
                  <div class="card h-100">
                     <!-- Product image-->
                     <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                     <!-- Product details-->
                     <div class="card-body p-4">
                        <div class="text-center">
                           <!-- Product name-->
                           <h5 class="fw-bolder">Product</h5>
                        </div>
                     </div>
                     <!-- Product actions-->
                     <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Remove from collection</a></div>
                     </div>
                  </div>
               </div>
               <div class="col mb-5">
                  <div class="card h-100">
                     <!-- Product image-->
                     <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                     <!-- Product details-->
                     <div class="card-body p-4">
                        <div class="text-center">
                           <!-- Product name-->
                           <h5 class="fw-bolder">Product</h5>
                        </div>
                     </div>
                     <!-- Product actions-->
                     <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Remove from collection</a></div>
                     </div>
                  </div>
               </div>

<style>
    .btn-outline-dark {
        color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;
    }
</style>
               
      </section>
      <!-- Bootstrap core JS-->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
      <!-- Core theme JS-->
      <script src="js/scripts.js"></script>
   </body>


<?php
include('footer.php');
?>