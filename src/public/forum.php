<?php
require(__DIR__ . "/../partials/nav.php");


// Check if user is logged in

logged_in(true);

// User is logged in, show the profile page
?>
<html>
<head>
<script>
  //validateJWT();
</script>
    <title>Forum</title>
</head>
<body>
    <h1>Welcome to the Forum!</h1>
    <p>Here you can particpate in discussions, sharing your thoughts and opinions on music with the AudioNook community.</p>
</body>
</html>
