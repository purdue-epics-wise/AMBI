<?php

namespace Core;

/**
 * Base Controller
 *
 * PHP verison 5.4
 */

 abstract class Controller {
    
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];
    
    /**
     * Class contructor
     *
     * @param array $route_params Parameters from the route
     *
     * @return void
     */
    public function __construct($route_params){
        $this->route_params = $route_params;
    }
    
    /**
     * When a method is non-public|non-existenst
     * this method is called
     *
     * This will help call before & after method
     * e.g. a user have to be authenticated before doing anything
     *
     * @param string $name methodname
     * @param array $args
     * @return void
     */
    public function __call($name, $args){
        
        $method = $name . 'Action';
        
        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " .get_class($this));
        }
    }
    
    /**
     * Before filter: called before action method
     *
     * @return void
     */
    protected function before(){
        
    }
    
    /**
     * After filter: called after action method
     *
     * @return void
     */
    protected function after(){
        
    }
}