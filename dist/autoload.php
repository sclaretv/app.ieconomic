<?php

    include_once 'controllers/Route.php';
    include_once 'config/access.php';
    include_once 'config/extra.php';
    include_once 'controllers/Connection.php';    
    include_once 'models/Ei_indicators.php';
    include_once 'models/Ei_indicators_details.php';

    class Autoload  extends Route { 

        public function execute_method(){
            $aux = $this->get_routes();
            
            if (!empty($aux)) {
                $file = ucfirst($aux[0]);
                $method = $aux[1];
                $classname = $file;
                $params = (!empty($aux[2]))?$aux[2]:null;

                if(is_file('controllers/'.$file.'.php')){
                    
                    include_once 'controllers/'.$file.'.php';

                    if (method_exists($classname,$method)) {
                        $obj = new $classname();
                        if(!empty($params)){
                            $obj->$method($params);
                        } else {
                            $obj->$method();
                        } 
                    }
                }                               
            }
        }
    }


    $obj = new Autoload();
    $obj->execute_method();