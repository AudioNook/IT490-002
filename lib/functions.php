
<?php

//Helps redirect.php find our base project dir path sincce 
$BASE_PATH = 'sample';


require(__DIR__ . "/get_url.php");
require(__DIR__ . "/redirect.php");
require(__DIR__ . "/db.php");

// Rabbit MQ
require(__DIR__ . "/rbMQclient.php");
require(__DIR__ . "/rbMQserver.php");

require(__DIR__ . "/handleLogin.php");


