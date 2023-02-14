#!/usr/bin/php
<?php
require(__DIR__ . "/../lib/functions.php");

$rbMQS = get_rbMQS();

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
        //Get Database
        $db = getDB();

        $stmt = $db->prepare("SELECT id, username, password FROM testusers WHERE username = :username");

            try{
                $r = $stmt->execute([":username" => $username]);
                if($r){
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if($user){
                        $pass = $user["password"];
                        if($pass == $password){
                            redirect(get_url($BASE_PATH . "/home.php"));
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
            }
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}





echo "testRabbitMQServer BEGIN".PHP_EOL;
$rbMQS->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>