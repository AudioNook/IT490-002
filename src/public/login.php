
<?php
require(__DIR__ . "/../partials/nav.php");
?>
<div id="alert_msg"></div>
<div class="container-fluid">
    <h1>Log in to your AudioNook account!</h1>
    <!-- <form method="POST"> -->
    <form onsubmit="return validate_login(this)" method="POST">
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" id="username" name="username"/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="pw">Password</label>
            <input class="form-control" type="password" id="password" name="password"/>
        </div>
        <input type="submit" name="submit" class="mt-3 btn btn-primary" value="Log in" />
    </form>
</div>

<!-- validation script added in utilities.js  -->


<?php
//check if the form is submitted
if (isset($_POST["username"]) && isset($_POST["password"])){
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
    switch (true){
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
    if(!$hasError){
    // Rabbit MQ Client Connection
    global $rbMQc;

    $msg = "Sending login request";

    $login_req = array();
    $login_req['type'] = 'login';
    $login_req['username'] = $username;
    $login_req['password'] = $password;
    $login_req['response'] = $msg;

    $response = json_decode($rbMQc->send_request($login_req), true);

    switch($response['code']){
        case 200:
            $token = $response['token'];
            $expiry = $response['expiry'];
            setcookie("jwt", $token, $expiry, "/");
            redirect(get_url("home.php"));
            break;
        case 401:
            echo '<script language="javascript">';
            echo 'alert("' . $response['message'] . '")';
            echo '</script>';
            break;
        default:
            echo($response['message']);

    }
    
    }else {
        echo '<script language="javascript">';
        echo 'alert("' . $errorMsg . '")';
        echo '</script>';
    }

    }

?>

<?php
include('footer.php');
?>