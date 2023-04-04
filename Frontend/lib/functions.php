
<?php

//Helps redirect.php find our base project dir path sincce 
$BASE_PATH = '/frontend/public';

// Utility functions
require(__DIR__. "/utils/util_functions.php"); 

// User functions
require(__DIR__ . "/user_functions.php");

// Request Handling functions
require(__DIR__ . "/request_handling/request_functions.php");

// Forum Handling functions
require(__DIR__ . "/review_functions.php");