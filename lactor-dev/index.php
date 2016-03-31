<?php

 
// Twig
require_once './vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

// Autoloader
spl_autoload_register(function ($class){
    $root = '.'; // root is current file
    $class_file = str_replace('\\', '/', $class);
    $file = "$root/$class_file.php";
    if (is_readable($file)) {
        require $file;
    }
});


// Error and Exception Handling
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();

$router->add('',['controller'=>'Home', 'action'=>'index']);
$router->add('authenticate', ['controller'=>'Authenticate', 'action'=>'index']);
$router->add('diary', ['controller'=>'Diary', 'action'=>'index']);
$router->add('{controller}/{action}');
$router->add('admin/{controller}/{action}', ['namespace'=>'Admin']);



$router->dispatch($_SERVER['QUERY_STRING']);


 