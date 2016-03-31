<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Authenticate as AuthenModel;
use \Core\URL;
/**
 * Autheticate controller
 *
 * PHP version 5.4
 */

class Authenticate extends \Core\Controller {
    
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
        
        if (!(isset($_POST['email']) && isset($_POST['password']))) {
            static::setLogin(3, 'please fill in this form');
            URL::redirect('');
        }
        
        if (!(static::validate($_POST['email'], $_POST['password']))) {
            unset($_SESSION['email']);
            unset($_SESSION['password']);
            static::setLogin(2, 'Email or password is incorrect');
            URL::redirect('');
        }
        static::setLogin(1, 'log in success');
        $_SESSION['email'] = $_POST['email'];
        $_SESSION['password'] = md5($_POST['password']);
        URL::redirect('');
    }
    
    /**
     * set log in type
     * @return void
     */
    private static function setLogin($loginType, $msg) {
        $_SESSION['loginType'] = $loginType;
        $_SESSION['message'] = $msg;
    }

    /**
     * Check email & password
     *
     * @param String $email
     * @param String $password
     *
     * @return boolean true if success
     */
    private static function validate($email, $password) {
        
        // filter email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        // check db
        if (!AuthenModel::authenticate($email, $password)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * check if the user has logged in
     *
     * @return boolean true if loggedIn
     */
    public static function isLogin() {
        return (isset($_SESSION['email']) && isset($_SESSION['password']));
    }
    
    
    
}