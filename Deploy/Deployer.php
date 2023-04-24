<?php

require_once 'Config.php';
/**
 * Class Deployer
 * @package Deploy
 * @property Config $config
 * Deploys the application to a remote server
 */
class Deployer
{
    private $config;

    /**
     * Deployer constructor.
     */
    public function __construct()
    {
        $this->config = new Config();
    }

    /**
     * Create a ZIP file of the source directory and store it locally
     */
    public function retrieve_source_zip()
    {
        $source_directory = $this->config->sourceDir;
        $local_directory = $this->config->localDir;
        $zip_file = 'myapp.zip';

        $source_connection = $this->connect_ssh($this->config->sourceHost, $this->config->sourceUser, $this->config->sourcePass);
        $this->create_zip($source_connection, $source_directory, $zip_file);
        $this->store_zip($source_connection, $zip_file, $local_directory . $zip_file);
        ssh2_exec($source_connection, 'rm ' . dirname($source_directory) . '/' . $zip_file);
        echo 'ZIP file retrieved and stored in local builds folder' . "\n";
    }

    /**
     * Transfer the ZIP file to the destination server and unzip it
     */
    public function send_to_dest($commands = [])
    {
        $local_directory = $this->config->localDir;
        $dest_directory = $this->config->destDir;
        $zip_file = 'myapp.zip';

        $dest_connection = $this->connect_ssh($this->config->destHost, $this->config->destUser, $this->config->destPass);
        $this->transfer_zip($dest_connection, $local_directory . $zip_file, $dest_directory . $zip_file);
        $this->unzip_file($dest_connection, $dest_directory . $zip_file, $dest_directory);
        $this->run_commands($dest_directory, $dest_connection, $commands);
        ssh2_exec($dest_connection, 'rm ' . $dest_directory . $zip_file);

        echo 'ZIP file transferred and extracted on destination server' . "\n";
    }
    /**
     * Run commands on the destination server
     * Take an array of commands as input
     * @param string $dir
     * @param resource $connection
     * @param array $commands
     */
    function run_commands($dir,$connection, $commands)
    {
        foreach ($commands as $command) {
            $result = ssh2_exec($connection, 'cd' . $dir . ' && ' . $command);
            stream_set_blocking($result, true);
            $output = stream_get_contents($result);
            if (!$output) {
                echo "Error executing command: " . $command . "\n";
            } else {
                echo "Command executed successfully: " . $command . "\n";
            }
        }
    }

    private function connect_ssh($host, $user, $pass)
    {
        $connection = ssh2_connect($host, 22);
        if (!ssh2_auth_password($connection, $user, $pass)) {
            echo 'Authentication failed for ' . $user . '@' . $host . "\n";
        }
        return $connection;
    }

    /**
     * Create a ZIP file of the source directory
     * @param resource $source_connection
     * @param string $source_directory
     * @param string $zip_file
     */
    private function create_zip($source_connection, $source_directory, $zip_file)
    {
        $source_parent_directory = dirname($source_directory);
        $source_folder_name = basename($source_directory);
        $zip_command = 'cd ' . $source_parent_directory . ' && zip -r ' . $zip_file . ' ' . $source_folder_name;
        $result = ssh2_exec($source_connection, $zip_command);
        stream_set_blocking($result, true);
        $output = stream_get_contents($result);
        if (!$output) {
            echo "Error creating ZIP file on source server: " . $output . "\n";
        }
    }

    private function store_zip($source_connection, $remote_zip_file, $local_zip_file)
    {
        if (!ssh2_scp_recv($source_connection, $remote_zip_file, $local_zip_file)) {
            echo 'Failed to retrieve remote ZIP file' . "\n";
        }
    }

    /**
     * Transfer the ZIP file to the destination server
     * @param resource $dest_connection
     * @param string $local_zip_file
     * @param string $remote_zip_file
     */
    private function transfer_zip($dest_connection, $local_zip_file, $remote_zip_file)
    {
        if (!ssh2_scp_send($dest_connection, $local_zip_file, $remote_zip_file)) {
            echo 'File upload failed to destination server' . "\n";
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

/*
$deployer = new Deployer();
$deployer->retrieve_source_zip();
$deployer->send_to_dest();
*/