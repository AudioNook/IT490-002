<?php
// JWT Secret
define('JWT_SECRET', 'this-is-the-secret');

// DB Credentials

$dotenv = @parse_ini_file(__DIR__ . "/.env");

// load local .env file
$dbhost = $dotenv["DB_HOST"];
$dbuser = $dotenv["DB_USER"];
$dbpass = $dotenv["DB_PASS"];
$dbdatabase = $dotenv["DB_DATABASE"];
$connection_string = "mysql:host=$dbhost;dbname=$dbdatabase;charset=utf8mb4";

// Rabbit MQ Configs

$rabbit_ini = "rabbitMQ.ini";
$rabbit_server = "testServer";


//echo "rabbit_ini = $rabbit_ini \n";
//echo "rabbit_server = $rabbit_server \n";



?>