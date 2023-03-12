
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
    $rbMQc = get_rbMQc();

    $msg = "Sending login request";

    $login_req = array();
    $login_req['type'] = 'login';
    $login_req['username'] = $username;
    $login_req['password'] = $password;
    $login_req['response'] = $msg;

    $response = $rbMQc->send_request($login_req);
    //echo($response);

    switch($response){
        case "valid":
            //?? redirect to login.php??
            redirect(get_url("home.php"));
            break;
        case "invalid_pass":
            echo '<script language="javascript">';
            echo 'alert("Wrong paswword. Politely FUCK OFF")';
            echo '</script>';
            break;
        case "invalid_user":
            echo '<script language="javascript">';
            echo 'alert("Username not not found. Politely FUCK OFF")';
            echo '</script>';
            break;
        default:
            echo($response);

    }
    
    
    //print_r($response);

    /* Get Database
    $db = getDB();

    $stmt = $db->prepare("SELECT id, username, password FROM testusers WHERE username = :username");

        try{
            $r = $stmt->execute([":username" => $username]);
            if($r){
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if($user){
                    $pass = $user["password"];
                    if($pass == $password){
                        redirect(get_url("home.php"));
                        exit(0);
                    } else {
                        echo '<script language="javascript">';
                        echo 'alert("Wrong paswword. Politely FUCK OFF")';
                        echo '</script>';
                    } 
                }else {
                    echo '<script language="javascript">';
                    echo 'alert("Username not not found. Politely FUCK OFF")';
                    echo '</script>';
                }
            }
        }
        catch (Exception $e){
            echo '<script language="javascript">';
            echo 'alert("<pre>" . var_export($e, true) . "</pre>")';
            echo '</script>';
        }*/
    }

?>
