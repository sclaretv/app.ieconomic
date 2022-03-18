
<?php

    class Ei_indicators_details_model extends Connection { 
        
        public $table = 'ei_indicators_details';  
        public $subject = 'Detalle de indicadores';      
        public $pk = 'id_id';
        public $key = 'id_id';
        public $index = 'ei_indicators_details.id_id';
    
        public $fields = array(
                                'i_id', // int 11
                                'id_value', // decimal 10,2
                                'id_date', // datetime
                                'id_add', // datetime
                                'id_update', // timestamp
                            );


        function __construct() {

        }

        
        public function create($params = array()){
            $out = null;
            if (!empty($params)) {     
                
                $connect = $this->connect();     

                if($connect){
                    $columns = implode(",", array_keys($params));

                    $result = $this->mysqli->query('INSERT INTO '.$this->table.' ('.$columns.')  VALUES ('.$params['i_id'].', '.$params['id_value'].', "'.$params['id_date'].'", "'.$params['id_add'].'");');

                    if($result){
                        $out = $this->mysqli->insert_id;
                    }

                    $this->disconnect();
                }
            }
            return $out;
        }

    
    }

?>