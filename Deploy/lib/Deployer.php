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
    private $devConfig;
    private $qaConfig;
    private $prodConfig;

    /**
     * Deployer constructor.
     */
    public function __construct()
    {
        $this->devConfig = new Config('dev');
        $this->qaConfig = new Config('qa');
        $this->prodConfig = new Config('prod');
    }
    
}

/*
$deployer = new Deployer();
$deployer->retrieve_source_zip();
$deployer->send_to_dest();
*/