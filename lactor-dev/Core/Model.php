<?php

namespace Core;

use PDO;
use App\Config;
/**
 * Base model
 *
 * PHP version 5.4
 */
abstract class Model {
    
    /**
     * Get PDO db conn
     * @return mixed
     */
    protected static function getDB(){
        
        static $db = null;
        
        if ($db === null) {
            
            $dsn = 'mysql:host=' .Config::DB_HOST .';dbname=' .Config::DB_NAME  .';charset=utf8';
            
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);
            
            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        }
        return $db;
    }
    
    /**
     * Get MySQL conn
     * @return conn
     */
    protected static function getConn() {
        
        static $conn = null;
        
        if ($conn === null) {
            
            $conn = new \mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME);
            
            if ($conn->connect_error) {
                throw new \Exception("Couldn't connect to database");
            }
        }
        return $conn;
    
    }
}
