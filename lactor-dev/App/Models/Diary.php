<?php

namespace App\Models;

use PDO;

/**
 * Post model
 *
 * PHP v5.4
 */
class Diary extends \Core\Model {
    
    /**
     * Get all the posts as an assoc array
     *
     * @return array
     */
    public static function getAll(){
       
        try {
            $db = static::getDB(); //function of parent
            
            $statement = $db->query('SELECT id, title, content FROM posts
                                    ORDER BY created_at');
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            return $results;
            
        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}