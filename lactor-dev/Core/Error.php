<?php

namespace Core;

/**
 * Error and exception handler
 *
 * PHP version 5.4
 */
class Error {
    
    /**
     * Error handler. Convert all erors to Exeptions by throwing an ErrorException
     *
     * @param int $level Error level
     * @param string $message Error message
     * @param string $file Filename the error was raised in
     * @param int $line Line number in the file
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line) {
        
        if (error_reporting() !== 0) { // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);    
        }
    }
    
    /**
     * Exeption handler
     *
     * @param Exception $exception The exception
     *
     * @return void
     */
    public static function exceptionHandler($exception) {
        
        // Code: 404=>not found, 500=>server error
        $code = $exception->getCode();
        if ($code != 404) {$code = 500;}
        http_response_code($code);
        
        if (\App\Config::SHOW_ERRORS) {
            static::errorBrowser($exception);
        } else {
            static::errorServer($exception);
            View::renderTemplate("$code.html"); //because same namespace
        }
        
    }
    
    
    /**
     * print exception details on browser,
     * good for developers
     * 
     * @param Exception $exception
     *
     * @return void
     */
    private static function errorBrowser($exception) {
        echo "<h1>Fatal error</h1>";
        echo "<p>Uncaught exception: '" .get_class($exception) ."'</p>";
        echo "<p>Message: '" .$exception->getMessage() ."'</p>";
        echo "<p>Stack trace: <pre>" .$exception->getTraceAsString() ."</pre></p>";
        echo "<p>Thrown in '" .$exception->getFile() ."' on line " .$exception->getLine() ."</p>";
    }
    
    /**
     * send the exception details to server, logs file
     *
     * @param Exception $exception
     * 
     * @return void
     */
    private static function errorServer($exception) {
        
        $log = './logs/' .date('Y-m-d') .'.txt';
        ini_set('error_log', $log);
        $message  = "\n**************************************************"; 
        $message .= "\nUncaught exception: '" .get_class($exception) ."'";
        $message .= "\nMessage: '" .$exception->getMessage() ."'";
        $message .= "\nStack trace: " .$exception->getTraceAsString();
        $message .= "\nThrown in '" .$exception->getFile() ."' on line " .$exception->getLine();
        error_log($message);
    }
    
}