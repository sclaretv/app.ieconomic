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
                    $sql = 'INSERT INTO '.$this->table.' ('.$columns.')  VALUES 
                    ('.intval($params['i_id']).', 
                    '.$params['id_value'].', 
                    "'.$params['id_date'].'", 
                    "'.$params['id_add'].'");';
                    
                    $result = $this->mysqli->query($sql);

                    if($result){
                        $out = $this->mysqli->insert_id;
                    }

                    $this->disconnect();
                }
            }
            return $out;
        }


        public function update($params = array(), $id_id){
            
            $out = null;
            if (!empty($params)) {     
                
                $connect = $this->connect();     

                if($connect){

                    $result = $this->mysqli->query('UPDATE '.$this->table.' SET id_value = "'.$params['id_value'].'", id_update = "'.$params['id_update'].'" WHERE id_id = '.$id_id.';');

                    if($result){
                        $out = $this->mysqli->affected_rows;
                    }

                    $this->disconnect();
                }
            }
            return $out;
        }

    
    }

