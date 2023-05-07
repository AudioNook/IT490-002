<?php
namespace DMZ;
use Exception;

class DMZConfig{
    public $user_agent;
    public $key;
    public $secret;

    public function __construct()
    {
        // Load local .env file
        try{
            $dotenv = @parse_ini_file(__DIR__ . "/../../env.ini");
            //error_log("Dotenv: " . print_r($dotenv, true));
            // DB Credentials
            $this->user_agent = $dotenv["USER_AGENT"];
            $this->key = $dotenv["KEY"];
            $this->secret = $dotenv["SECRET"];
        } catch (Exception $e) {
            error_log("Error loading .env file: " . $e->getMessage());
        }
    }
}