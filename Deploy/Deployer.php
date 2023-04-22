<?php

require_once 'Config.php';

class Deployer
{
    private $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    public function retrieve_source_zip()
    {
        $source_directory = $this->config->sourceDir;
        $local_directory = $this->config->localDir;
        $zip_file = 'myapp.zip';

        $source_connection = $this->connect_ssh($this->config->sourceHost, $this->config->sourceUser, $this->config->sourcePass);
        $this->create_zip($source_connection, $source_directory, $zip_file);
        $this->store_zip($source_connection, $zip_file, $local_directory . $zip_file);
        ssh2_exec($source_connection, 'rm ' . dirname($source_directory) . '/' . $zip_file);
        echo 'ZIP file retrieved and stored in local builds folder';
    }

    public function send_to_dest()
    {
        $local_directory = $this->config->localDir;
        $dest_directory = $this->config->destDir;
        $zip_file = 'myapp.zip';

        $dest_connection = $this->connect_ssh($this->config->destHost, $this->config->destUser, $this->config->destPass);
        $this->transfer_zip($dest_connection, $local_directory . $zip_file, $dest_directory . $zip_file);
        $this->unzip_file($dest_connection, $dest_directory . $zip_file, $dest_directory);
        ssh2_exec($dest_connection, 'rm ' . $dest_directory . $zip_file);

        echo 'ZIP file transferred and extracted on destination server';
    }

    private function connect_ssh($host, $user, $pass)
    {
        $connection = ssh2_connect($host, 22);
        if (!ssh2_auth_password($connection, $user, $pass)) {
            die('Authentication failed for ' . $user . '@' . $host);
        }
        return $connection;
    }

    private function create_zip($source_connection, $source_directory, $zip_file)
    {
        $source_parent_directory = dirname($source_directory);
        $source_folder_name = basename($source_directory);
        $zip_command = 'cd ' . $source_parent_directory . ' && zip -r ' . $zip_file . ' ' . $source_folder_name;
        $result = ssh2_exec($source_connection, $zip_command);
        stream_set_blocking($result, true);
        $output = stream_get_contents($result);
        if (!$output) {
            die("Error creating ZIP file on source server: " . $output);
        }
    }

    private function store_zip($source_connection, $remote_zip_file, $local_zip_file)
    {
        if (!ssh2_scp_recv($source_connection, $remote_zip_file, $local_zip_file)) {
            die('Failed to retrieve remote ZIP file');
        }
    }

    private function transfer_zip($dest_connection, $local_zip_file, $remote_zip_file)
    {
        if (!ssh2_scp_send($dest_connection, $local_zip_file, $remote_zip_file)) {
            die('File upload failed to destination server');
        }
    }

    private function unzip_file($dest_connection, $zip_file, $extract_to)
    {
        $temp_folder = $extract_to . 'temp_extract/';
        $create_temp_folder = 'mkdir -p ' . $temp_folder;
        ssh2_exec($dest_connection, $create_temp_folder);
        
        $unzip_command = 'unzip -o ' . $zip_file . ' -d ' . $temp_folder;
        ssh2_exec($dest_connection, $unzip_command);
        
        $move_command = 'mv ' . $temp_folder . '*/* ' . $extract_to;
        ssh2_exec($dest_connection, $move_command);
        
        $remove_temp_folder = 'rm -r ' . $temp_folder;
        ssh2_exec($dest_connection, $remove_temp_folder);
    }
            
    
}

$deployer = new Deployer();
$deployer->retrieve_source_zip();
$deployer->send_to_dest();
