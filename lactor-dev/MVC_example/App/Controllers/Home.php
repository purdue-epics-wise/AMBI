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
        
        $params = ['title'=>'Welcome',
                   'message'=>'Please sign in this form',
                   'form'=>true];
        
        if (isset($_POST['user'])) {
            
            if (strlen($_POST['user']) == 0) {
                $params['message'] = 'please type your name ^^';
                unset($_SESSION['user']);
            } else {
                $params['message'] = 'connected!!';
                $params['name'] = $_POST['user'];
                $params['form'] = false;
                $_SESSION['user'] = $_POST['user'];
            }
        }

            
        View::renderTemplate('Home/index.html', $params);
    }
    
    
    
    
}