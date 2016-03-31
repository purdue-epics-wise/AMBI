<?php

namespace App\Controllers\Admin;

use \Core\View;

/**
 * Admin Home controller
 *
 * PHP version 5.4
 */
class Home extends \Core\Controller {
    
    /**
     * Before filter
     *
     * @return void, or boolean
     */
    protected function before(){
        //return false
    }
    
    /**
     * After filter
     *
     * @return void
     */
    protected function after(){
        //echo "(after) ";
    }
    
    /**
     * show index page
     *
     * @return void
     */
    public function indexAction() {
        $params = ['title'=>'Admin',
                   'message'=>'this is admin page'];
        View::renderTemplate('Home/index.html', $params);
    }
    
    
}
