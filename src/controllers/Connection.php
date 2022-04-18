<?php

    class Connection {
        
        public $server_access = (ACCESS_PRODUCTION['status'])?ACCESS_PRODUCTION:ACCESS_DEFAULT;        

        public function connect(){   
            
            $this->mysqli = new mysqli($this->server_access['servername'], $this->server_access['username'], $this->server_access['password'], $this->server_access['database']);
            $this->mysqli->set_charset("utf8");
            $status = ($this->mysqli->connect_error)?false:true; 
            
            return $status;
        }


        public function disconnect(){
            $this->mysqli->close();               
        }
        
    }    