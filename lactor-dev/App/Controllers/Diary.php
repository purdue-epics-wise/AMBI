<?php

namespace App\Controllers;

use \Core\View;
use \Core\URL;
use \App\Models\Diary as DiaryModel; 
/**
 * Diary controller
 *
 * PHP version 5.4
 */

class Diary extends \Core\Controller {
    
    /**
     * Before filter
     *
     * @return void, or false
     */
    protected function before() {
        session_start();
        if (!Authenticate::isLogin()) {
            URL::redirect('authenticate');
            return false;
        }
    }
     
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction() {
        echo 'This is Dairy index page';
    }
    
    
    
}