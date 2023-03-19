
<?php

//Helps redirect.php find our base project dir path sincce 
$BASE_PATH = '/src/public';

// Utility functions
require(__DIR__. "/utils/util_functions.php"); 

// User functions
require(__DIR__ . "/user/user_functions.php");

// Rabbit MQ functions
require(__DIR__ . "/rbMQ/rabbitmq_functions.php");

// Request Handling functions
require(__DIR__ . "/request_handling/request_functions.php");

// JWT session functions
require(__DIR__ . "/jwt_handling/jwt_functions.php");

// Forum Handling functions
require(__DIR__ . "/forum_handling/forum_functions.php");