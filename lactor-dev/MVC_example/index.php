<?php

/**
 * Front Controller
 * @author Minh <cnguyenm@purdue.edu>
 * PHP v5.5
 */

/**
 * Twig
 */
require_once './vendor/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

/**
 * Autoloader
 *
 * this function will run when a class is not found
 * e.g $home = new App\Controllers\Home();
 * run: require '/App/Controllers/Home()';
 */
spl_autoload_register(function ($class){
    $root = '.'; // root is current file
    $class_file = str_replace('\\', '/', $class);
    $file = "$root/$class_file.php";
    if (is_readable($file)) {
        require $file;
    }
});


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();

$router->add('',['controller'=>'Home', 'action'=>'index']);
$router->add('{controller}/{action}');
$router->add('admin/{controller}/{action}', ['namespace'=>'Admin']);

echo 'Query string: '.$_SERVER['QUERY_STRING'] .'<br>';
$router->dispatch($_SERVER['QUERY_STRING']);


 