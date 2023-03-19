<?php
require(__DIR__ . "/../partials/nav.php");

global $rbMQc;

$msg = "Sending discussion topic request";

$topic_req = array();
$topic_req['type'] = 'topics';

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

?>
<html>
<head>
<script>
  //validateJWT();
</script>
    <title>Audio Nook Forums</title>
</head>
<body>
    <h1>Forums</h1>
    <p>Here, you can participate in discussions, sharing your thoughts and opinions on music with the AudioNook community.</p>
</body>
</html>
