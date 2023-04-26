<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function get_db()
{
    global $db;
    if (isset($db)) {

        try {
            require_once(__DIR__ . "/../Config.php");
            $dbCredentials = new Config('db');
            $db = new PDO($dbCredentials->connection_string, $dbCredentials->dbuser, $dbCredentials->dbpass);
        } catch (PDOException $e) {
            error_log("Database error: " . var_export($e, true));
            $db == null;
        }
    }
    return $db;
}
