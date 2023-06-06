<?php
// Create a simple router to navigate through pages
class Router
{
    private $routes = [];
    
    static $_ROOT = '../';
    static $_SRC = '../src/';

    public function addRoute($url, $handler) {
        if (!array_key_exists($url, $this->routes))
        {
            $this->routes[$url] = $handler;
            return true;
        }
        return false;
    }

    public function handleRequest($url) {
        if (isset($this->routes[$url])) {
            $handler = $this->routes[$url];
            // Call the handler or perform any desired action
            
            // Example: include a PHP file as the handler
            include $handler;
        } else {
            echo "No route found for URL: $url";
        }
    }

    static function goto($url)
    {
        include_once Router::$_SRC.$url;
    }

    static function header()
    {
        Router::goto("includes/header.html");
    }

    static function footer()
    {
        Router::goto("includes/footer.html");
    }
}
?>