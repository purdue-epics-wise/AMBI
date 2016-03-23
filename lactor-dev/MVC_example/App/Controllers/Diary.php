<?php

namespace App\Controllers;

use \Core\View;        //view
use \App\Models\Diary as DiaryModel; //models
/**
 * Home controller
 *
 * PHP version 5.4
 */

class Diary extends \Core\Controller {
    
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
    public function viewAction() {
        $params = [
            'title'=>'Diary',
            'message'=>'You can view your diary here'
        ];
        if (!isset($_SESSION['user'])) {
            $params['message'] = 'Please go back to home page to fill in your name';
        }
        
        View::renderTemplate('Diary/View.html',$params);
    }
    
    public function stuffAction() {
        $params = DiaryModel::getAll(); //param will have id, title, content
        View::renderTemplate('Diary/DiaryStuff.html',[
                                'posts' => $params                      
                                ]);
    }
    
    
}