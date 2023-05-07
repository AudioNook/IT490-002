<?php
ob_start();
require(__DIR__ . "/../partials/nav.php");
use PragmaRX\Google2FA\Google2FA;
?>
<div id="alert_msg">
    <?php if (isset($errorMsg)) : ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($errorMsg) ?>
    </div>
    <?php endif; ?>
</div>
<div class="container-sm">
    <div class="mb-3">
        <h1>Log in to your AudioNook account!</h1>
        <!-- <form method="POST"> -->
        <form id="loginForm" onsubmit="return validate_login(this)" method="POST">
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input class="form-control" type="text" id="username" name="username" required/>
            </div>
            <div class="mb-3">
                <label class="form-label" for="pw">Password</label>
                <input class="form-control" type="password" id="password" name="password" required/>
            </div>
            <!-- Button trigger modal -->
            <button type="button" class="mt-3 btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                Login
            </button>
        </form>
    </div>
</div>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="staticBackdropLabel">Insert Verification Code:</h5>
                <div class="mb-3">
                    <input class="form-control" type="text" id="twoFA" name="twoFA" form="loginForm" required/>
                </div>
                <button type="button" class="mt-3 btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="verify" class="mt-3 btn btn-primary" form="loginForm">Verify</button>
            </div>
        </div>
    </div>
</div>
<?php
//check if the form is submitted
if (isset($_POST["username"]) && isset($_POST["password"])) {
    // if we have in input for username && password get the DB
    // and select the associated record where the username matched in the DB

    //Grabbing username and password
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email;
    $opt = $_POST['twoFA'];
    
    // add check for username contains '@'
    // if so, sanitze the email

    //Server Validation
    $hasError = false;
    switch (true) {
        case empty($username):
            $hasError = true;
            $errorMsg = "Username cannot be empty.";
            break;
            // add check for valid username
        case empty($password):
            $hasError = true;
            $errorMsg = "Password cannot be empty.";
            break;
        case empty($opt):
            $hasError = true;
            $errorMsg = "Verification Code cannot be empty.";
            break;
            // add case for checking valid password
    }
    $request = new DBRequests();
    $userinfo = $request->getByUsername($username);
    $gkey = $userinfo[0]['gkey'];
    $g2fa = new Google2FA();
    $isvalid = $g2fa->verifyKey($gkey, $opt);
    if (!$isvalid) {
        $hasError = true;
        $errorMsg = "Invalid Verification Code.";
    }
    //If there are no validation errors
    if (!$hasError) {
        // Rabbit MQ Client Connection
        $request->login($username, $password);
    } 
}
?>
<?php
include('footer.php');
ob_end_flush();
?>