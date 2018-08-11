<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}else {
    $dir = dirname(__DIR__). '/vendor/Zend/library/';
    $paths = array("$dir", ".");
    set_include_path(implode(PATH_SEPARATOR, $paths));

    putenv("ZF2_PATH=" . $dir);
    chdir(dirname(__DIR__));
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
