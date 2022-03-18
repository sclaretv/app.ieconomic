<?php

    class Route {


        private $basepath;
        private $uri;
        private $base_url;
        private $routes;
        private $route;


        private function _get_current_uri(){
            $this->basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
            $this->uri = substr($_SERVER['REQUEST_URI'], strlen($this->basepath));
            if (strstr($this->uri, '?')) $this->uri = substr($this->uri, 0, strpos($this->uri, '?'));
            $this->uri = trim($this->uri, '/');
            return $this->uri;
        }


        public function get_routes(){
            $this->base_url = $this->_get_current_uri();
            $this->routes = explode('/', $this->base_url);
            return $this->routes;
        }
        

    }   
    
?>