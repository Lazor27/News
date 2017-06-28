<?php

class View {

    
    protected $data;

 
    protected $path;

    
    public function __construct($data = array() , $path = 0)
    {
        if (!$path) {
            //$path = default path ...
            $path = self::getDefaultViewPath();
        }
        if (!file_exists($path)) {
            throw new Exception('Template file is not found in path: ' . $path);
        }

        $this->data = $data;
        $this->path = $path;
    }

   
    protected static function getDefaultViewPath()
    {
        $router = App::getRouter();

        if (!$router) {
            return false;
        }

        // get address of the view file
        $controller_name_dir = $router->getController();
        $template_name = $router->getMethodPrefix().$router->getAction().'.html';
        return VIEWS_PATH.DS.$controller_name_dir.DS.$template_name;
    }

  
    public function render(){
        // this variable will be available in in a template
        $data = $this->data;

        // buffering start,
        ob_start();
        include_once VIEWS_PATH.DS."parts".DS."header.html";
        include_once VIEWS_PATH.DS."parts".DS."flash_message.html";
        include($this->path);
        
        $content = ob_get_clean();
        return $content;
    }
}