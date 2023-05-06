<?php
require(__DIR__ . "/../partials/nav.php");
$qrCode = null;
?>
<div id="alert_msg"></div>
<div class="container-fluid">
    <h1>Register</h1>
    <!-- <form method="POST"> -->
    <!-- validation script to be added for onsubmitt-->
        <form onsubmit="return validate_register(this)" method="POST">
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" placeholder= "JohnDoe" id="username" name="username"/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="text" placeholder="user@domain.com" id="email" name="email"/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" type="password" placeholder="P@ssword1" id="password" name="password"/>
            <label class="form-label" for="confirm">Confirm Password</label>
            <input class="form-control" type="password" placeholder="P@ssword1" id="confirm" name="confirm"/>

        </div>
        <input type="submit" name="submit" class="mt-3 btn btn-primary" value="register"/>
    </form>
</div>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content shadow p-3 mb-5 border-0" style="width: 18rem;">
    <img class="card-img-top" src="qrcode.svg" alt="QR Code">
      <div class="modal-body">
      <h1 class="heading"><strong>Scan QR Code</strong></h1>
      <p class="card-text">Scan the QR code set up Two Factor Authentication</p>
      </div>
      <div class="modal-footer">
        <a type="button" href="<?php echo get_url('profile.php'); ?>" class="btn btn-primary">Scanned</a>
      </div>
    </div>
  </div>
</div>


<?php
if (isset($_POST["username"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])){
    
    //grabbing email, username, password, and confirm password fields from form
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];
    $patternName = '/^[a-z0-9_-]{3,16}$/';
    $patternPassword = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/';

    // sanitize email here
    
    //Server Validation
    $hasError = false;
    switch (true){
        case empty($username):
            $hasError = true;
            $errorMsg = "Username cannot be empty.";
            break;
        // add case for checking valid username
        case empty($email):
            $hasError = true;
            $errorMsg = "Email cannot be empty.";
            break;
        //add case for checking valid email
        case empty($password):
            $hasError = true;
            $errorMsg = "Password cannot be empty.";
            break;
        // add case for checking valid password
        case (empty($confirm) || $confirm !== $password):
            $hasError = true;
            $errorMsg = "Passwords do not match.";
            break;
         //case for checking username against regEx   
         case((preg_match($patternName, $username) == 0)):
            $hasError = true;
            $errorMsg = "Invalid Username Format.";
            break;
        //case for checking username against regEx   
        case((preg_match($patternPassword, $password) == 0)):
            $hasError = true;
            $errorMsg = "Invalid Password Format.";
            break;
    }
    

    //If there are no validation errors
    if(!$hasError){
        $request = new DBRequests();
        $qrCodeUrl = $request->register($username, $email, $password);
        echo '<script>';
        echo 'var otpModal = new bootstrap.Modal(document.getElementById("staticBackdrop"));';
        echo 'otpModal.show();';
        echo '</script>';
        //do bootstrap modal popup here
        } else {
            echo '<script language="javascript">';
            echo 'alert("' . $errorMsg . '")';
            echo '</script>';
        }

}
?>
