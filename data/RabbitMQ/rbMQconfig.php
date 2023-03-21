<?php
$rabbit_ini = "rabbitMQ.ini";
$rabbit_ini2 = "rabbitMQ.ini";
$rabbit_server = "testServer";
$rabbit_FELS = 'FELogServer';
$rabbit_APILS = 'APILogServer';
$rabbit_DBLS = 'DBLogServer';
$rabbit_RBMQLS = 'RBMQLogServer';


require(__DIR__ . "/path.inc");
require(__DIR__ . "/get_host_info.inc");
require_once(__DIR__ . "/rabbitMQLib.inc");
