<?php

    include_once 'controllers/Route.php';
    include_once 'config/database.php';
    include_once 'config/extra.php';
    include_once 'controllers/Connection.php';    
    include_once 'models/Ei_indicators.php';
    include_once 'models/Ei_indicators_details.php';
    include_once 'controllers/Commands.php';
    include_once 'controllers/Api_indicators.php';

    class Autoload  extends Route { 

        public function execute_method(){
            $aux = $this->get_routes();
            if (!empty($aux)) {
                $method = $aux[1];
                $obj = new $aux[0]();
                $obj->$method();
            } else {
                die();
            }
        }
    }


    $obj = new Autoload();
    $obj->execute_method();