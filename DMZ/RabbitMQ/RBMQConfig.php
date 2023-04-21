<?php
namespace RabbitMQ;
class RBMQConfig{
    public $rabbit_ini = "rabbitMQ.ini";
    public $rabbit_ini2 = "rabbitMQ.ini";
    public $rabbit_server = "AudioDB";
    public $rabbit_FELS = 'FELogServer';
    public $rabbit_APILS = 'APILogServer';
    public $rabbit_DBLS = 'DBLogServer';
    public $rabbit_RBMQLS = 'RBMQLogServer';
    public $jwtServer = 'AudioDMZ';
    public $OLServer = 'onLoadServer';
}



/*
    rabbit_ini = "rabbitMQ.ini";
    $rabbit_ini2 = "rabbitMQ.ini";
    $rabbit_server = "testServer";
    $rabbit_FELS = 'FELogServer';
    $rabbit_APILS = 'APILogServer';
    $rabbit_DBLS = 'DBLogServer';
    $rabbit_RBMQLS = 'RBMQLogServer';
    $jwtServer = 'jwtServer';
    $OLServer = 'onLoadServer';

    require(__DIR__ . "/path.inc");
    require(__DIR__ . "/get_host_info.php");
    require_once(__DIR__ . "/rabbitMQLib.php");
*/
