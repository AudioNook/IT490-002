<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function get_db()
{
    global $db;
    if (!isset($db)) {
        try {
            $db = new PDO("mysql:host=127.0.0.1;dbname=AudioNook_DB;charset=utf8mb4", "audionook", "pass123");
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . var_export($e, true));
            $db = null;
        }
    }
    if ($db === null) {
        error_log("Error: \$db is null");
    }
    return $db;
}

