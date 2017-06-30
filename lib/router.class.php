<?php

class Router {

    protected $uri;

    protected $controller;

    protected $action;

    
    protected $params;

    protected $router;

    protected $method_prefix;

    protected $language;

    
    public function __construct($uri) 
    {
        
        $this->uri = urldecode(trim($uri,'/'));

        $routes = Config::get('routes');

        $this->router        = Config::get('default_router');
        $this->method_prefix = Config::get('default_prefix');
        $this->language      = Config::get('default_language');
        $this->controller    = Config::get('default_controller');
        $this->action        = Config::get('default_action');

        
        $uri_parts = explode('?', $this->uri);

        
        $path = $uri_parts[0];
        $path_parts = explode('/',$path);

        

        if (count($path_parts)) {

            
            if (in_array( strtolower(current($path_parts)) , array_keys($routes))) {

                
                $this->router = strtolower(current($path_parts));
                $this->method_prefix = isset($routes[$this->router]) ? $routes[$this->router] : '';
                array_shift($path_parts);
            } 

            
            if (current($path_parts)) {
                $this->controller = strtolower(current($path_parts));
                array_shift($path_parts);
            }

            // Get next element это может быть только action
            if (current($path_parts)) {
                $this->action = strtolower(current($path_parts));
                array_shift($path_parts);
            }
            
            // Если еще остались элементы в массиве path_parts то могут быть только параметры
            $this->params = $path_parts;
        }
    }

    
    public function getUri() 
    {
        return $this->uri;
    }

    
    public function getController() 
    {
        return $this->controller;
    }

    
    public function getAction()
    {
        return $this->action;
    }

    
    public function getParams() 
    {
        return $this->params;
    }

    
    public function getRouter()
    {
        return $this->router;
    }

    
    public function getMethodPrefix()
    {
        return $this->method_prefix;
    }

    
    public function getLanguage()
    {
        return $this->language;
    }

    
    public static function redirect($location)
    {
        header("Location: $location");
    }
}