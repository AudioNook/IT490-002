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
    <title>My Profile</title>
</head>
<body>
    <h1>Welcome to your profile!</h1>
    <p>Here you can view and edit your account information.</p>
</body>
</html>
