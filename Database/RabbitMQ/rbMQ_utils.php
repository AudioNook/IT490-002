<?php
namespace RabbitMQ;

spl_autoload_register(function ($class) {
    if (substr($class, 0, strlen(__NAMESPACE__)) === __NAMESPACE__) {
        $classWithoutNamespace = str_replace(__NAMESPACE__ . '\\', '', $class);
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . $classWithoutNamespace . '.php';

        if (is_readable($filePath)) {
            require_once $filePath;
        }
    }
});
