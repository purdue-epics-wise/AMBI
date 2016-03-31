<?php

namespace App\Models;

use App\Config;
use PDO;

/**
 * Authenticate model
 */
class Authenticate extends \Core\Model {
    
    /**
     * Authenticate email & password
     *
     * @return boolean true if success
     */
    public static function authenticate($email, $password){
        
        $password = hash("sha256", $password .Config::SALT);
        try {
            $db = static::getDB();
            $stmt = $db->prepare("SELECT * FROM Mothers WHERE email = :email AND password = :password;");
            $stmt->bindParam(':email', $inputEmail);
            $stmt->bindParam(':password', $inputPass);
            $inputEmail = $email;
            $inputPass = $password;
            $result = $stmt->execute();
        } catch(PDOException $e) {
            echo $e->getMessage();
        }
        
        return $result;
    }
}