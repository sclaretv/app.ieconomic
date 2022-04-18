<?php

    class Route {


        private $basepath;
        private $uri;
        private $base_url;
        private $routes;
        private $route;


        private function _get_current_uri(){
            $this->basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
            
            if(php_sapi_name() == "cli"){
                $this->uri = $_SERVER['argv'][1];
                $this->uri = explode('/', $this->uri);
                
            }else{
                $this->uri = substr($_SERVER['REQUEST_URI'], strlen($this->basepath));
                if (strstr($this->uri, '?')) $this->uri = substr($this->uri, 0, strpos($this->uri, '?'));
                $this->uri = trim($this->uri, '/');
                $this->uri = explode('/', $this->uri);
            }
           
            return $this->uri;
        }


        public function get_routes(){
            $this->base_url = $this->_get_current_uri();            
            return $this->base_url;
        }
        

    }   