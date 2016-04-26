<?php

namespace App\Controllers;

use \Core\View;
/**
 * Home controller
 *
 * PHP version 5.4
 */

class Home extends \Core\Controller {
    
    /**
     * Before filter
     *
     * @return void, or false
     */
    protected function before(){
        session_start();
    }
    
    /**
     * After filter
     *
     * @return void
     */
    protected function after() {
        
    }
    
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction() {
        
        $params = [
            'autoModal' => false,
			'message' => "fill in please"
        ];
        if (isset($_SESSION['loginType'])) {
            $params['message'] = $_SESSION['message'];
            if ($_SESSION['loginType'] !== 1) {
                $params['autoModal'] = true;
            }
            
        }
        View::renderTemplate('Home/index.html', $params);
    }
    
    /**
     *
     */
   
    
    
    
    
}