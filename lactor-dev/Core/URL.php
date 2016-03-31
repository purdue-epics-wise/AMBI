<?php

namespace Core;

use App\Config;
/**
 * URL class
 */
class URL {
    
    /**
     * redirect to file in document root
     * @param String $direction
     */
    public static function redirect($direction) {
    
        $root = Config::ROOT;
        $url = $root .$direction;
        header("Location: $url");
        die();
    }
    
    /**
     * redirect to outside URL
     * @param String $direction
     */
    public static function linkDirect($direction) {
        
    }
}
