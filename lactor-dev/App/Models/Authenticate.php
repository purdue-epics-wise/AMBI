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
        
        $conn = static::getConn();
        $password = hash("sha256", $password .Config::SALT);
        
        $result = $conn->query("SELECT * FROM Mothers WHERE email = '" . $email ."' AND password = '".$password. "';");
        
        if ($result->num_rows == 0) {
            return false;
        }
        return true;
        
    }
}