<?php

require_once __DIR__ . '/Config.php';
require_once __DIR__ . '/Zip.php';
require_once __DIR__ . '/NiceSSH.php';
/**
 * Class Deployer
 * @package Deploy
 * @property Config $config
 * Deploys the application to a remote server
 */
class Deployer
{
    private $devConfig;
    private $qaConfig;
    private $prodConfig;
    // Target Folder to retrieve from and send to
    //TODO: Change this to the actual target folder: /var/www/audionook/
    private $targetDir = "/var/www/audionook/";
    // Local Folder to store on Deployment Server
    private $localDir = '';
    /**
     * Deployer constructor.
     */
    function __construct()
    {
        $this->devConfig = new Config('dev');
        $this->qaConfig = new Config('qa');
        $this->prodConfig = new Config('prod');
        $this->localDir = realpath(__DIR__ . "/../builds") . '/';
    }

    function deploy_from($cluster, $type = null)
    {
        switch ($cluster) {
            case 'dev':
                if (is_null($type)) {
                    echo "Deploying DEV to QA\n";
                    $this->deploy_cluster($cluster, $this->devConfig, $this->qaConfig);
                } else {
                    echo "Deploying DEV $type to QA\n";
                    $this->deploy_package($cluster, $type, $this->devConfig, $this->qaConfig);
                }
                break;
            case 'qa':
                if (is_null($type)) {
                    echo "Deploying QA to PROD\n";
                    $this->deploy_cluster($cluster, $this->qaConfig, $this->prodConfig);
                } else {
                    echo "Deploying QA $type to PROD\n";
                    $this->deploy_package($cluster, $type, $this->qaConfig, $this->prodConfig);
                }
                break;
            default:
                echo 'Invalid cluster';
                return;
        }
    }
    function deploy_cluster($cluster, $srcConf, $destConf)
    {
        //retrieve and stores zips from source
        $packages = $this->retrieve_zips($cluster, $srcConf);
        // send zips and unzip at destination
        $this->send_zips($cluster, $packages, $destConf);
        echo "Deployed to $cluster\n";
    }
    function deploy_package($cluster, $type, $srcConf, $destConf)
    {
        //retrieve and stores zips from source
        $version = $this->get_version_num();
        $package[] = $this->retrieve_zip($cluster, $srcConf, $type, $version);
        $this->insert_into_db($cluster, $type, $version, $package[0]);
        // send zips and unzip at destination
        $this->send_zip($cluster, $package, $destConf, $type);
    }
    function retrieve_zip($cluster, $srcConf, $package_type, $version)
    {
        $zip = new Zip();
        $ssh = new NiceSSH();

        $session = $ssh->start_session($srcConf->{$package_type . 'Host'}, $srcConf->{$package_type . 'User'}, $srcConf->{$package_type . 'Pass'});
        $package = "{$cluster}_{$package_type}_{$version}.zip";
        $create_zip = $zip->create_zip($this->targetDir, $package);
        $ssh->exec_command($session, $create_zip);
        echo "Created $package \n";
        $ssh->retrieve_file($session, dirname($this->targetDir) . '/' . $package, $this->localDir . $package);
        $ssh->remove_file($session, dirname($this->targetDir) . '/' . $package);
        echo "Retrieved and Locally Stored $package_type Package\n";

        return $package;
    }


    function retrieve_zips($cluster, $srcConf)
    {
        $packages = [];
        $version = $this->get_version_num();
        // DB package retrieval
        $dbPackage = $this->retrieve_zip($cluster, $srcConf, 'db', $version);
        $packages[] = ['type' => 'db', 'package_name' => $dbPackage];

        // DMZ package retrieval
        $dmzPackage = $this->retrieve_zip($cluster, $srcConf, 'dmz', $version);
        $packages[] = ['type' => 'dmz', 'package_name' => $dmzPackage];

        // FE session and package retrieval
        $fePackage = $this->retrieve_zip($cluster, $srcConf, 'fe', $version);
        $packages[] = ['type' => 'fe', 'package_name' => $fePackage];

        $this->insert_into_db($cluster, $packages, $version);

        return $packages;
    }

    function get_version_num()
    {
        require_once __DIR__ . '/utils/get_db.php';
        $db = get_db();
        try {
            // Retrieve the maximum version number for the given cluster
            $stmt = $db->prepare('SELECT MAX(version) FROM deployments WHERE environment = ?');
            $stmt->bindParam(1, $cluster);
            $stmt->execute();
            $max_version = $stmt->fetchColumn();
            // Increment the version number for each package being deployed
            $version = $max_version + 1;
            return $version;
        } catch (PDOException $e) {
            error_log("Database error: " . var_export($e, true));
        }
    }

    function insert_into_db($cluster, $packages, $version)
    {
        require_once __DIR__ . '/utils/get_db.php';
        $db = get_db();
        try {

            // Start the transaction
            $db->beginTransaction();

            // Insert the deployments
            $stmt = $db->prepare('INSERT INTO deployments (environment, package_type, version, package_name) VALUES (?, ?, ?, ?)');
            foreach ($packages as $package) {
                $stmt->bindParam(1, $cluster);
                $stmt->bindParam(2, $package['type']);
                $stmt->bindParam(3, $version);
                $stmt->bindParam(4, $package['package_name']);
                $stmt->execute();
            }

            // Commit the transaction
            $db->commit();

            echo "Inserted into deployments table";
        } catch (PDOException $e) {
            // Roll back the transaction
            $db->rollBack();
            error_log("Database error: " . var_export($e, true));
            $this == null;
        }
    }

    function send_zips($cluster,$packages, $destConf)
    {
        foreach ($packages as $package) {
            $this->send_zip($cluster,$package, $destConf, $package['type']);
        }
    }

    function send_zip($cluster, $package, $destConf, $package_type)
    {
        $zip = new Zip();
        $ssh = new NiceSSH();

        $session = $ssh->start_session($destConf->{$package_type . 'Host'}, $destConf->{$package_type . 'User'}, $destConf->{$package_type . 'Pass'});
        $ssh->remove_dir($session, $this->targetDir);
        echo "Sending {$package['package_name']} to {$destConf->{$package_type . 'Host'}}\n";
        $ssh->send_file($session, $this->localDir . $package['package_name'], $this->targetDir . $package['package_name']);
        $unzip = $zip->unzip($this->targetDir . $package['package_name'], $this->targetDir);
        $ssh->exec_commands($session, $unzip);
        $ssh->remove_file($session, $this->targetDir . $package['package_name']);
        $ssh->exec_command($session, 'cd ' . $this->targetDir . ' && composer install');
        $this->cluster_commands($cluster, $package_type, $ssh,$session, $destConf);
        echo "Deployed {$package['package_name']} to {$destConf->{$package_type . 'Host'}}\n";
    }

    function cluster_commands($cluster, $package_type, $ssh,$session, $conf){
        switch ($package_type) {
            case 'fe':
                //restart apache
                $ssh->exec_command($session, 'echo \'' . $conf->{$package_type . 'Pass'} . '\' | sudo -S service apache2 restart');
                $this->send_rbmq_ini($conf,$cluster,$ssh,$session);
                break;
            case 'dmz':
                // TODO: place holder for dmz restart
                break;
            case 'db':
                // TODO: place holder for db restart
                break;
            default:
                echo 'Invalid package type';
                return;
        }
    }
    function send_rbmq_ini($cluster,$ssh,$session){
        $rbmqDir = "RabbitMQ/lib";
        $rbMQini = "RabbitMQ/lib/rabbitMQ.ini";
        $ssh->remove_file($session, $this->targetDir . $rbMQini);
        $ssh->send_file($session, "/cluster_inis", $rbmqDir);
        //rename the file from devRabbitMQini to just rabbitMQ.ini
        $ssh->exec_command($session, "mv " . $this->targetDir . $rbmqDir . "/{$cluster}RabbitMQ.ini " . $this->targetDir . $rbmqDir . "/rabbitMQ.ini");
    }

    function rollback_version($version)
    {
        require_once __DIR__ . '/utils/get_db.php';
        $db = get_db();
        try {
            $stmt = $db->prepare('SELECT environment, package_type, package_name FROM deployments WHERE version = ?');
            $stmt->bindParam(1, $version);
            $stmt->execute();
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $environment = $packages[0]['environment'];
            $destConf = null;
            switch ($environment) {
                case 'dev':
                    $destConf = $this->devConfig;
                    break;
                case 'qa':
                    $destConf = $this->qaConfig;
                    break;
                default:
                    echo 'Invalid environment';
                    return;
            }
            $this->send_zips($cluster, $packages, $destConf);
        } catch (PDOException $e) {
            error_log("Database error: " . var_export($e, true));
        }
    }

    function rollback_package($id)
    {
        require_once __DIR__ . '/utils/get_db.php';
        $db = get_db();
        try {
            $stmt = $db->prepare('SELECT environment, package_type, package_name FROM deployments WHERE id = ?');
            $stmt->bindParam(1, $id);
            $stmt->execute();
            $package = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$package) {
                echo 'Invalid ID';
                return;
            }

            $environment = $package['environment'];
            $package_type = $package['package_type'];
            $destConf = null;
            switch ($environment) {
                case 'dev':
                    $destConf = $this->devConfig;
                    break;
                case 'qa':
                    $destConf = $this->qaConfig;
                    break;
                default:
                    echo 'Invalid environment';
                    return;
            }

            $this->send_zip($cluster, $package, $destConf, $package_type);
        } catch (PDOException $e) {
            error_log("Database error: " . var_export($e, true));
        }
    }
}
