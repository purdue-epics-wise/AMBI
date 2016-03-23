<?php

namespace Core;

/**
 * Router
 * @author Minh
 * PHP verison 5.4
 */

 class Router {
    
    /**
     * Associative array of routes (the routing table)
     * @var array
     */
    protected $routes = [];
    
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $params = [];
    
    /**
	 * convert route to reg_exp, add to routing table
	 * @param String $route The route URL
	 * @param assoc array $params Parameters (controller, action, namespace,...)
	 *
	 * @return void
	 */
    
    public function add($route, $params = []) {
        
        // Convert route to a regular expression: escape forward slashes
		// ex: {controller}/{action} -> {controller}\/{action}
		$route = preg_replace('/\//', '\\/', $route);
		
		// Convert variables e.g {controller}
		$route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
		
		// Convert variables with custom reg_exp e.g {id:\d+}
		$route = preg_replace('/\{([a-z]+):([^\}]+)\}/' , '(?P<\1>\2)', $route);
		
		// Add start and end delimiters, and case insensitive flag
		$route = '/^' . $route . '$/i';
		
		$this->routes[$route] = $params;
    }
    
    /**
	 * Get all the routes from the routing table
	 * @return array 
	 */
	public function getRoutes(){
		return $this->routes;
	}
    
    /**
     * Check if route match routes in routing table
     * And set $params
     *
     * @param String $url The url
     *
     * @return boolean true if a match if found
     *
     * .e.g url "diary/view" match
     * set $this->params = ['controller'=>'diary','action'=>'view']
     */
    public function match($url) {
        
        foreach ($this->routes as $route => $params){
			if (preg_match($route, $url, $matches)){
			
                foreach ($matches as $key => $match) {
                    if (is_string($key)){
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
			}
		}
		
		return false;
    }
    
    /**
	 * Get the currently matched parameters
	 *
	 * @return associative array
	 */
	public function getParams(){
		return $this->params;
	}
    
    /**
	 * Dispatch the url (controller, action) to
	 * class name of controller <StudlyCaps>
	 * name of method <camelCase>
	 *
	 * @param string $url The route url
	 *
	 * @return void
	 */
	public function dispatch($url){
		
		$url = $this->removeQueryStringVariables($url);
		
		if ($this->match($url)) {
			$controller = $this->params['controller'];
			$controller = $this->convertToStudlyCaps($controller);
			//$controller = "App\Controllers\\$controller";  add namespace
            
			$controller = $this->getNamespace() .$controller;
			if (class_exists($controller)) {
				$controller_object = new $controller($this->params); 
				
				$action = $this->params['action'];
				$action = $this->convertToCamelCase($action);
				
				// if object->method() is callable in case it is private or protected
				if (is_callable([$controller_object, $action])) {
					$controller_object->$action();
				} else {
					//echo "Method $action (in controller $controller) not found";
					throw new \Exception("Method $action (in controller $controller) not found");
				}
			} else {
				//echo "Controller class $controller not found";
				throw new \Exception("Controller class $controller not found");
			}
		} else {
			throw new \Exception('No route matched. ', 404);
		}
	}
    
    /**
	 * Convert string with hyphens to StudlyCaps
	 * e.g. post-authors => PostAuthors
	 *
	 * @param string $string the string to convert
	 *
	 * @return string
	 */
	protected function convertToStudlyCaps($string){
		/* .i.e input: post-new
		 * 1: post new
		 * 2: Post New
		 * 3: PostNew
		 */
		return str_replace(' ','', ucwords(str_replace('-',' ',$string)));
	}
    
    /**
	 * Convert the string with hyphens to camelCase
	 * e.g. add-new => addNew
	 *
	 * @param string $string The string to convert
	 *
	 * @return string
	 */
	protected function convertToCamelCase($string){
		
		return lcfirst($this->convertToStudlyCaps($string));
	}
    
    /**
	 *	Before, we replace '?' in query string to '&'
	 *	url								QUERY_STRING		routing table
	 *	------------------------------------------------------------------
	 *	localhost/?page=1				page=1				''
	 *	localhost/posts?page=1			posts&page=1		posts
	 *	localhost/posts/index			posts/index			posts/index
	 *	localhost/posts/index?page=1	posts/index&page=1	posts/index
	 *
	 *	A URL of format localhost/?page (one var name, no value) won't
	 *	work
	 *
	 *	@param string $url The full URL
	 *
	 *	@return string URL with query string var removed
	 */
	protected function removeQueryStringVariables($url){
		if ($url != '') {
			$parts = explode('&', $url, 2); // same as String.split("&")
			
			if (strpos($parts[0], '=') === false) {
				$url = $parts[0];
			} else {
				$url = '';
			}
		}
		return $url;
	}
    
    /**
	 * Get namespace for controller class.
	 * The namespace defined in route param is added if present
	 *
	 * @return string The request URL
	 */
	protected function getNamespace(){
		$namespace = '\App\Controllers\\';
		
		if (array_key_exists('namespace', $this->params)) {
			$namespace .= $this->params['namespace'] .'\\';
		}
		return $namespace;
	}
    
 }