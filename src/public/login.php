
<?php
require(__DIR__ . "/../partials/nav.php");
?>
<div class="container-fluid">
    <h1>Login</h1>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" id="username" name="username"/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="pw">Password</label>
            <input class="form-control" type="password" id="password" name="password"/>
        </div>
        <input type="submit" name="submit" class="mt-3 btn btn-primary" value="Login" />
    </form>
</div>

<!-- validation script to be added -->

<?php
//check if the form is submitted
if (isset($_POST['submit'])){
    // if we have in input for username && password get the DB
    // and select the associated record where the username matched in the DB

// $response = "unsupported request type, politely FUCK OFF";
    
    $username = $_POST['username'];
    $password = $_POST['password'];

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
            redirect(get_url("profile.php"));
            break;
        case 401:
            echo '<script language="javascript">';
            echo 'alert("' . $response['message'] . '")';
            echo '</script>';
            break;
        default:
            echo($response['message']);

    }
    
    }

?>
