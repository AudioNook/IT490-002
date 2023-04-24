<?php

class Config
{
    // Sending VM credentials
    public $sourceHost;
    public $sourceUser;
    public $sourcePass;
    // Folder to zip and send
    public $sourceDir;

    // Local Folder to store on Deployment Server
    public $localDir = __DIR__ . "/builds/";

    // Recieving VM credentials
    public $destHost;
    public $destUser;
    public $destPass;
    // Folder to unzip the file
    public $destDir;

    //DB Crenetials
    public $connection_string;
    public $dbhost;
    public $dbuser;
    public $dbpass;
    public $dbdeploy;
    public $dbdatabase;

    public function __construct()
    {
        try {
            $dotenv = @parse_ini_file(__DIR__ . "/.env.deploy");
            $this->sourceHost = $dotenv["SOURCE_HOST"];
            $this->sourceUser = $dotenv["SOURCE_USER"];
            $this->sourcePass = $dotenv["SOURCE_PASS"];
            $this->sourceDir = "/var/www/audionook/";

            $this->destHost = $dotenv["DEST_HOST"];
            $this->destUser = $dotenv["DEST_USER"];
            $this->destPass = $dotenv["DEST_PASS"];
            $this->destDir = "/var/www/audionook/";

            $this->dbhost = $dotenv["DB_HOST"];
            $this->dbuser = $dotenv["DB_USER"];
            $this->dbpass = $dotenv["DB_PASS"];
            $this->dbdeploy = $dotenv["DB_DATABASE"];
            $this->dbdatabase = $dotenv["DB_DEPLOY"];
            $this->connection_string = 
                "mysql:
                host=$this->dbhost;
                dbname=$this->dbdeploy;
                charset=utf8mb4";

        } catch (Exception $e) {
            error_log("Error loading .env file: " . $e->getMessage());
        }
    }
}
