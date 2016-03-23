<?php

/**
 * Localhost only
 */
$host = 'localhost';
$db_name = 'mvc';
$user = 'root';
$password = '';


try {
    $db = new PDO("mysql:host=$host;dbname=$db_name",$user,$password);
    echo "Connected successfully, OK";
} catch (PDOException $e){
    echo $e->getMessage();
}