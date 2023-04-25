<?php
/**
 * Class NiceSSH
 * refrences: https://www.php.net/manual/en/ref.ssh2.php
 */
class NiceSSh{
    /**
     * Connect to server via SSH
     * @param string $host
     * @param string $user
     * @param string $pass
     * @return resource
     */
    public function start_session($host, $user, $pass){
        echo 'Connecting to ' . $user . '@' . $host . "\n";
        // starts session with an SSH server
        // https://www.php.net/manual/en/function.ssh2-connect.php
        $session = ssh2_connect($host, 22);

        // ssh2_auth_password() - authenticate over SSH using a plain password
        // https://www.php.net/manual/en/function.ssh2-auth-password.php
        if(!ssh2_auth_password($session, $user, $pass)){
            echo 'Authentication failed for ' . $user . '@' . $host . "\n";
        }
        return $session;
    }
    /**
     * Execute a command on a remote server
     * @param resource $session
     * @param string $command
     */
    public function exec_command($session,$command){
        // executes a command on a remote server
        // https://www.php.net/manual/en/function.ssh2-exec.php
        ssh2_exec($session, $command);
    }
    /**
     * Execute multiple commands on a remote server
     * @param resource $session
     * @param array $commands
     */
    public function exec_commands($session,$commands){
        // executes multiple commands on a remote server
        // https://www.php.net/manual/en/function.ssh2-exec.php
        foreach($commands as $command){
            ssh2_exec($session, $command);
        }
    }

    /**
     * Create a ZIP file of a directory on a remote server
     * @param resource $session
     * @param string $dir
     * @param string $zip_file
     */
    public function retrieve_file($session, $remote_file, $local_file){
        // retrieves a file from the remote server
        // https://www.php.net/manual/en/function.ssh2-scp-recv.php
        if(!ssh2_scp_recv($session, $remote_file, $local_file)){
            echo 'Failed to retrieve remote file ' . $remote_file . "\n";
        }
    }

    /**
     * Send a file to a remote server
     * @param resource $session
     * @param string $local_file
     * @param string $remote_file
     */
    public function send_file($session, $local_file, $remote_file){
        // sends a file from the local server to the remote server
        // https://www.php.net/manual/en/function.ssh2-scp-send.php
        if(!ssh2_scp_send($session, $local_file, $remote_file)){
            echo 'Failed to send file ' . $local_file . ' to ' . $remote_file . "\n";
        }
    }
}