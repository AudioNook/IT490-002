<?php
ob_start();
require(__DIR__ . "/../partials/nav.php");
use PragmaRX\Google2FA\Google2FA;
$request = new DBRequests();
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
        <form id="loginForm" method="POST">
            <div class="mb-3">
                <label class="form-label" for="username">Username</label>
                <input class="form-control" type="text" id="username" name="username" required />
            </div>
            <div class="mb-3">
                <label class="form-label" for="pw">Password</label>
                <input class="form-control" type="password" id="password" name="password" required />
            </div>
            <input type="submit" class="mt-3 btn btn-primary" value="Log in" />
        </form>
    </div>
</div>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="staticBackdropLabel">Insert Verification Code:</h5>
                <form id="verifyForm" method="POST">
                    <div class="mb-3">
                        <input class="form-control" type="text" id="twoFA" name="twoFA" required />
                    </div>
                    <button type="button" class="mt-3 btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="verify" class="mt-3 btn btn-primary">Verify</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#loginForm").on("submit", function(event) {
            event.preventDefault();

            // Send login request
            $.ajax({
                type: "POST",
                url: window.location.href,
                data: $(this).serialize(),
                success: function() {
                    // Show verification modal
                    $("#staticBackdrop").modal("show");
                }
            });
        });

        $("#staticBackdrop").on("shown.bs.modal", function() {
            // Send verification request
            $.ajax({
                type: "POST",
                url: window.location.href,
                data: $("#verifyForm").serialize(),
                success: function(response) {
                    if (response.success) {
                        // Redirect to home page
                        window.location.href = "/";
                    } else {
                        // Show error message
                        $("#alert_msg").html('<div class="alert alert-danger" role="alert">' + response.message + '</div>');
                    }
                }
            });
        });
    });
</script>
<?php
//check if the form is submitted
if (isset($_POST["username"]) && isset($_POST["password"])) {
    // if we have in input for username && password get the DB
    // and select the associated record where the username matched in the DB

    //Grabbing username and password
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email;

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
            // add case for checking valid password
    }

    //If there are no validation errors
    if (!$hasError) {
        // Rabbit MQ Client Connection
        $request->login($username, $password);
    }
}
if (isset($_POST['verify'])) {
    $opt = $_POST['twoFA'];
    $uid = get_user_id();
    $userinfo = $request->getByUserId($uid);
    $gkey = $userinfo['gkey'];
    $g2fa = new Google2FA();
    $isvalid = $g2fa->verifyKey($gkey, $opt);
    if (!empty($opt)) {
        if (!$isvalid) {
            $errorMsg = "Invalid Verification Code.";
            $request->logout();
        } else {
            redirect("profile.php");
        }
    } else {
        $errorMsg = "Verification Code cannot be empty.";
    }
}
?>
<?php
include('footer.php');
ob_end_flush();
?>