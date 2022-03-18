<?php

    class Connection {
        
        public $server_access = (ACCESS_DEFAULT['status'])?ACCESS_DEFAULT:ACCESS_PRODUCTION;        

        public function connect(){   
            $this->mysqli = new mysqli($this->server_access['servername'], $this->server_access['username'], $this->server_access['password'], $this->server_access['database']);
            $status = ($this->mysqli->connect_error)?false:true;            
            return $status;
        }


        public function disconnect(){
            $this->mysqli->close();               
        }
        
    }    

?>