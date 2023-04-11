<?php
require_once(__DIR__ . "/../vendor/autoload.php");
use RabbitMQ\rabbitMQClient;

function rbmqc_db(){
    $rbMQc = null;
    try{
        $rbMQc = new rabbitMQClient("testRabbitMQ.ini", "testServer");
        return $rbMQc;
    } catch (Exception $e){
        echo "Error: " . $e->getMessage();
        return $rbMQc;
    }
}

function rbmqc_dmz(){
    $rbMQc = null;
    try{
        $rbMQc = new rabbitMQClient("testRabbitMQ.ini", "testServer");
        return $rbMQc;
    } catch (Exception $e){
        echo "Error: " . $e->getMessage();
        return $rbMQc;
    }
}

function rbmqc_log(){
    $rbMQc = null;
    try{
        $rbMQc = new rabbitMQClient("testRabbitMQ.ini", "testServer");
        return $rbMQc;
    } catch (Exception $e){
        echo "Error: " . $e->getMessage();
        return $rbMQc;
    }
}