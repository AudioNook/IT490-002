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

<?php
include('footer.php');
?>