<?php

    class Ei_indicators_model extends Connection { 
        
        public $table = 'ei_indicators'; 
        public $table_alias =  'ei_indicators as i';
        public $subject = 'Indicadores';      
        public $pk = 'i_id';
        public $key = 'i_id';
        public $index = 'ei_indicators.i_id';
    
        public $fields = array(
                                'i_code', // varchar 50
                                'i_name', // varchar 50
                                'i_unit_measure', // varchar 50 
                                'i_description', // text
                                'i_status', // tinyint 1 
                                'i_add', // datetime
                                'i_update', // timestamp
                            );

        
        function __construct() {
            
        }
                            
        
        public function get_by_params($params = array(), $joins = true, $group_by = null){
            
            $sql = 'SELECT * FROM '.$this->table_alias;
            if ($joins) {
                $sql .= ' LEFT JOIN ei_indicators_details AS id ON id.i_id=i.i_id';
            }

            $sql .= $this->_filters($params);

            if(!empty($group_by)){
                $sql .= ' GROUP BY '.$group_by;
            }

            $sql .= ' ORDER BY i.i_id ASC, id.id_date DESC';
            
            $connect = $this->connect();     
            $rows = array();

            if($connect){
                $result = $this->mysqli->query($sql);       
                         
                $this->disconnect();
                while($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
            }

            return $rows;
        }


        private function _filters($params=array()){
            
            $sql = ' WHERE i.i_status=1';

            if (!empty($params['i_code'])) {
                $sql .= ' AND i.i_code="'.$params['i_code'].'"';
            }
            
            if (!empty($params['id_value'])) {
                $sql .= ' AND id.id_value='.$params['id_value'];
            }

            if (!empty($params['id_date'])) {
                $date = new DateTime($params['id_date']);
                $id_date = date_format($date,"Y-m-d H:i:s");
                $sql .= ' AND id.id_date="'.$id_date.'"';
            }

            if (!empty($params['year'])) {
                $sql .= ' AND YEAR(id.id_date)="'.$params['year'].'"';
            }

            if (!empty($params['month'])) {
                if (is_array($params['month'])) {
                    $columns = implode('","', array_values($params['month']));
                    $sql .= ' AND MONTH(id.id_date) IN ("'.$columns.'")';
                } else {
                    $sql .= ' AND MONTH(id.id_date)="'.$params['month'].'"';
                }
            }

            if (isset($params['more_recent'])) {
                if ($params['more_recent']) {
                    $sql .= ' AND id.id_date = (SELECT MAX(id2.id_date) FROM ei_indicators_details AS id2 WHERE id2.i_id = i.i_id)';
                }
            }

            return $sql;
        }


        public function create($params = array()){
            $out = null;
            if (!empty($params)) {     
                
                $connect = $this->connect();     

                if($connect){
                    $columns = implode(",", array_keys($params));

                    $result = $this->mysqli->query('INSERT INTO '.$this->table.' ('.$columns.')  VALUES ("'.$params['i_code'].'", "'.$params['i_name'].'", "'.$params['i_unit_measure'].'", '.$params['i_status'].', "'.$params['i_add'].'");');

                    if($result){
                        $out = $this->mysqli->insert_id;
                    }

                    $this->disconnect();
                }

            }
            return $out;
        }

    }

