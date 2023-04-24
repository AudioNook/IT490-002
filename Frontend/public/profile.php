<?php require(__DIR__ . "/../partials/nav.php");
logged_in(true);

$email = 'email not found';
$username = 'username not found';

$collection = [];

$user_id = get_user_id();
if (!empty($user_id) && !is_null($user_id)) {
   $profileRequest = new DBRequests();
   $creds = $profileRequest->getByUserId($user_id);
   //var_dump($creds);
   $email = $creds['email'];
   $username = $creds['username'];
   $results = $profileRequest->getCollection($user_id);
   $collection = $results['collection'];
}
$collection_count = count($collection);
//var_dump($collection);

?>

<html>

<head>
   <title>My Profile</title>
   <style>
      /* TODO move to style sheet */
      .image-tile {
         overflow: hidden;
         position: relative;
      }

      .image-tile .overlay {
         opacity: 0;
         background-color: rgba(0, 0, 0, 0.6);
         transition: opacity 0.3s ease;
         position: absolute;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         display: flex;
         flex-direction: column;
         justify-content: center;
         align-items: center;
      }

      .image-tile:hover .overlay {
         opacity: 1;
      }
   </style>

</head>
<section class="h-100 gradient-custom-2">
   <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
         <div class="col col-lg-9 col-xl-7">
            <div class="card">
               <div class="rounded-top text-white d-flex flex-row" style="background-color: #000; height:200px;">
                  <div class="ms-4 mt-5 d-flex flex-column" style="width: 150px;">
                     <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png" alt="Generic placeholder image" class="img-fluid img-thumbnail mt-4 mb-2" style="width: 150px; z-index: 1">
                     <button type="button" class="btn btn-outline-dark" data-mdb-ripple-color="dark" style="z-index: 1;">
                        Edit profile
                     </button>
                  </div>
                  <div class="ms-3" style="margin-top: 130px;">
                     <h5><?php echo htmlspecialchars($username); ?></h5>
                     <p><?php echo htmlspecialchars($email); ?></p>
                  </div>
               </div>
               <div class="p-4 text-black" style="background-color: #f8f9fa;">
                  <div class="d-flex justify-content-end text-center py-1">
                     <div>
                        <p class="mb-1 h5"><?php echo $collection_count; ?></p>
                        <p class="small text-muted mb-0">Collected</p>
                     </div>
                     <div class="px-3">
                        <p class="mb-1 h5">1026</p>
                        <p class="small text-muted mb-0">Followers</p>
                     </div>
                     <div>
                        <p class="mb-1 h5">478</p>
                        <p class="small text-muted mb-0">Following</p>
                     </div>
                  </div>
               </div>
               <div class="card-body p-4 text-black">
                  <div class="mb-5">
                     <p class="lead fw-normal mb-1">About</p>
                     <div class="p-4" style="background-color: #f8f9fa;">
                        <p class="font-italic mb-1">Something</p>
                        <p class="font-italic mb-1">Something</p>
                        <p class="font-italic mb-0">Something</p>
                     </div>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-4">
                     <p class="lead fw-normal mb-0">My Collection</p>
                     <p class="mb-0"><a href="#!" class="text-muted">Show all</a></p>
                  </div>
                  <div class="row gx-2">
                     <?php foreach ($collection as $c) : ?>
                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 mb-5">
                           <div class="image-tile position-relative">
                              <img class="w-100 rounded-3" src="<?php echo stripslashes(htmlspecialchars($c['cover_image'])) ?>" alt="..." />
                              <div class="overlay d-flex flex-column justify-content-center align-items-center position-absolute w-100 h-100">
                                 <h5 class="fw-bolder mb-3 text-white"><?php echo htmlspecialchars($c['title']); ?></h5>
                                 <form method="POST" action="list_item.php?id=<?php echo (int)htmlspecialchars($c['id']) . "&uid=" . (int) htmlspecialchars(get_user_id()) ?>">
                                    <input type="submit" value="List" class="btn btn-outline-light" />
                                 </form>
                              </div>
                           </div>
                        </div>
                     <?php endforeach; ?>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

</body>

<?php
include('footer.php');
?>