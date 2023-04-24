
<?php

//Helps redirect.php find our base project dir path sincce 
$BASE_PATH = '/Frontend/public';

// Utility functions
require(__DIR__. "/utils/util_functions.php"); 

// Request Handling functions
require(__DIR__ . "/DBRequests.php" );
require(__DIR__ . "/DMZRequests.php" );