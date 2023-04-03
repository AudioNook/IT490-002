<?php
namespace Database;
spl_autoload_register(function ($class) {
    // Check if the requested class is in the same namespace as the current file
    if (substr($class, 0, strlen(__NAMESPACE__)) === __NAMESPACE__) {
        // Remove the namespace part from the requested class name
        $classWithoutNamespace = str_replace(__NAMESPACE__ . '\\', '', $class);
        
        // Construct the file path for the class
        $filePath = __DIR__ . DIRECTORY_SEPARATOR . $classWithoutNamespace . '.php';

        // Check if the file exists and is readable
        if (is_readable($filePath)) {
            require_once $filePath;
        }
    }
});