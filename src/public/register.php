<?php
require(__DIR__ . "/../partials/nav.php");
?>

<div class="container-fluid">
    <h1>Register</h1>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label" for="username">Username</label>
            <input class="form-control" type="text" id="username" name="username"/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Email</label>
            <input class="form-control" type="text" id="email" name="email"/>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">Password</label>
            <input class="form-control" type="password" id="password" name="password"/>
            <label class="form-label" for="confirm">Password</label>
            <input class="form-control" type="password" id="confirm" name="confirm"/>

        </div>
        <input type="submit" name="submit" class="mt-3 btn btn-primary" value="register" />
    </form>
</div>

<?php
if (isset($_POST['submit'])){

    //grabbing username, password, and confirm password fields from form
    $hasError = false;
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm'];

    //Server Validation
    if(empty($username)){
        $hasError = true;
        echo '<script language="javascript">';
            echo 'alert("Username is empty.")';
            echo '</script>';
    }
    if(empty($email)){
        $hasError = true;
        echo '<script language="javascript">';
            echo 'alert("Email is empty.")';
            echo '</script>';
    }
    if(empty($password)){
        $hasError = true;
        echo '<script language="javascript">';
            echo 'alert("Password is empty.")';
            echo '</script>';
    }
    if(empty($confirmPassword)|| $confirmPassword !== $password){
        $hasError = true;
        echo '<script language="javascript">';
        echo 'alert("Confirm password is empty or does not match password.")';
        echo '</script>';   
    }

    //IF there are no validation errors
    if(!$hasError){
        //opening a rabbitMQclient connection
        global $rbMQc;
        $msg = "Sending register request";

        //creating a register array to store values
        $register_req = array();
        $register_req['type'] = 'register';
        $register_req['username'] = $username;
        $register_req['password'] = $password;
        $register_req['response'] = $msg;
    
        //sending received form responses to rabbitMQ
        $response = json_decode($rbMQc->send_request($$register_req), true);

        //checking whether or not resgister was processed successfully/unsuccessfully
        switch($response['code']){
            case 200:
                redirect(get_url("login.php"));
                break;
            case 409:
                echo '<script language="javascript">';
                echo 'alert("' . $response['message'] . '")';
                echo '</script>';
                break;
            default:
                echo($response['message']);
    
          }
        }
}
?>
