<?php

// Error Outputing
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getDB()
{
    global $db;

    // returns our existing DB connection based on config
    // and assign it the varibale $db

    if (!isset($db)) {

        try {

            require_once(__DIR__ . "/../config.php"); // grabbing credentials
            // builds connection string from our variables host & database
            global $connection_string, $dbuser, $dbpass;
            // PDO creates a new connection to the DB
            $db = new PDO($connection_string, $dbuser, $dbpass);

            // the following overides fetching as both an indexed & associative array
            // only fetches an associated array
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } // if no errors then we're connected
        catch (Exception $e) {
            error_log("getDB() error: " . var_export($e, true));
            $db = null;
        }
    }

    return $db;
}

function executeQuery($query, $params = [],$returnLastInsertId = false)
{
    $db = getDB();
    $stmt = $db->prepare($query);
    try {
        $r = $stmt->execute($params);
        if ($r) {
            if ($returnLastInsertId) {
                return $db->lastInsertId();
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    } catch (PDOException $e) {
        error_log("Error executing query: " . $query);
        error_log(var_export($e, true));
    }
    return false;
}
?>