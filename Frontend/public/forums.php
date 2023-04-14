<?php
require(__DIR__ . "/../partials/nav.php");

//logged_in(true); TODO uncomment
$rbMQc = rbmqc_db();

$topics_req = array();
$topics_req['type'] = 'topics';
$topics_req['message'] = "Sending discussion topic request";
$topics;
$response = json_decode($rbMQc->send_request($topics_req), true);
$rbMQc->close();//TODO Warning: Undefined variable $rbMQc in /Users/luanda/IT490-002/Frontend/public/forums.php on line 10
//Call to a member function send_request() on null in /Users/luanda/IT490-002/Frontend/public/forums.php:10 Stack trace: #0 {main} thrown in /Users/luanda/IT490-002/Frontend/public/forums.php on line 10

  switch ($response['code']) {
    case 200:
      $topics = $response['topics'];
      break;
    case 401:
      echo '<script language="javascript">';
      echo 'alert("' . $response['message'] . '")';
      echo '</script>';
      break;
   default:
      echo ($response['message']);
  
}
//check_jwt($rbMQc);

?>
<html>

<head>
  <script>
    //validateJWT();
  </script>
  <title>Forums</title>
</head>
<div class="container-fluid">

  <body>
    <h1>Forums</h1>
    <p>Here you can particpate in discussions, sharing your thoughts and opinions on music with the AudioNook community.</p>
  </body>
</div>
<div class="container-fluid">
  <?php if (!empty($topics)) : ?>
    <?php foreach ($topics as $topic) : ?>
      <div class="container-fluid">
        <div>
          <div class="card m-3">
            <h5 class="card-header"><?php echo $topic['name']; ?></h5>
            <div class="card-body">
              <p class="card-text"><?php echo $topic['description']; ?></p>
              <a href="posts.php?id=<?php echo $topic['id']; ?>" class="btn btn-secondary">Discuss</a>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else : ?>
    <div>
      <div class="card m-3">
        <h5 class="card-header">Unable to Load Topics!</h5>
        <div class="card-body">
          <p class="card-text">Try again next semester.</p>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div> 
</html>

<?php
include('footer.php');
?>