<?php

namespace Core;

/**
 * View
 *
 * PHP version 5.4
 */
class View {
    
    /**
     * Render a view file: .html, .php
     * simply by require that file
     *
     * @param String $view The view file
     *
     * @return void
     */
    public static function render($view, $args=[]) {
        
        extract($args, EXTR_SKIP);
        // flag: EXTR_SKIP: If there is a collision, don't overwrite the existing variable. 
        $file = "./App/Views/$view"; // relative to index.php outside
        
        if (is_readable($file)) {
            require $file;
        } else {
            //echo "$file not found";
            throw new \Exception("$file not found");
        }
    }
    
    /**
     * Render a view template using Twig
     *
     * @param string $template The template file
     * @param array $args assoc array to display data (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args=[]) {
        
        static $twig = null; // static will help reuse
        
        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem('./App/Views');
            $twig = new \Twig_Environment($loader);
        }
        echo $twig->render($template, $args);
    }
    
}