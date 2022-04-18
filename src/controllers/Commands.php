<?php

    /* 
    
    Orden de ejecución:

    1. create_indicators
    2. Si es primera vez: update_year_values(array de años)
    2. Si NO es primera vez: update_current_values

    */

    class Commands {

        function __construct() {
            $this->indicators_model = new Ei_indicators_model();
            $this->indicators_details_model = new Ei_indicators_details_model();
        }


        // function initial for create the indicators base in the table ei_indicator
        public function create_indicators(){
            $out = [];
            $indicators = $this->_get_current_values();

            if (!empty($indicators)) {
                unset($indicators['version']);
                unset($indicators['autor']);
                unset($indicators['fecha']);

                foreach ($indicators as $key => $ind) {
                     
                    $aux = $this->indicators_model->get_by_params(array('i_code'=>$ind['codigo']), false);
                    
                    if (empty($aux)) {
                        // create indicator
                        $params = array(
                            'i_code' => $ind['codigo'],
                            'i_name' => $ind['nombre'],
                            'i_unit_measure' => $ind['unidad_medida'],
                            'i_status' => 1,
                            'i_add' => date("Y-m-d H:i:s"),
                        );
                        $create = $this->indicators_model->create($params);
                        if ($create) {
                            $out['success'][] = $params;
                        } else {
                            $out['fail'][] = $params;
                        }
                    }
                }
            }

            echo json_encode($out);
        }


        // update database with current values
        public function update_current_values(){
            $out = [];
            $indicators = $this->_get_current_values();

            unset($indicators['version']);
            unset($indicators['autor']);
            unset($indicators['fecha']);
            
            foreach ($indicators as $key => $ind) {
                
                $aux = $this->indicators_model->get_by_params(array('i_code'=>$ind['codigo']),false);
                
                if (!empty($aux[0])) {
                    // create indicator
                    $a = null;
                    $params = [];
                    $d = $this->indicators_model->get_by_params(array('i_code'=>$ind['codigo'],'id_date'=>date_format(date_create($ind['fecha']), "Y-m-d H:i:s")),true);

                    if (empty($d)) {
                        $params = array(
                            'i_id' => $aux[0]['i_id'],
                            'id_value' => $ind['valor'],
                            'id_date' => date_format(date_create($ind['fecha']), "Y-m-d H:i:s"),
                            'id_add' => date("Y-m-d H:i:s"),
                        );
                        $a = $this->indicators_details_model->create($params);
                    } else {
                        $params = array(
                            'id_value' => $ind['valor'],
                            'id_update' => date("Y-m-d H:i:s"),
                        );
                        $a = $this->indicators_details_model->update($params, $d[0]['id_id']);
                    }

                    if ($a) {
                        $out['success'][] = $params;
                    } else {
                        $out['fail'][] = $params;
                    }                          
                                                          
                }                
            }

            echo json_encode($out);
        }


        // get current values in the api
        private function _get_current_values() {
            $api_url = 'https://mindicador.cl/api';
            if ( ini_get('allow_url_fopen') ) {
                $json = file_get_contents($api_url);
            } else {
                $curl = curl_init($api_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $json = curl_exec($curl);
                curl_close($curl);
            }            
            $daily_indicators = json_decode($json, true);
            return $daily_indicators;
        }


        // update database with year values
        public function update_year_values($year){
            $out = [];

            $indicators_db = $this->indicators_model->get_by_params(array(),false);                           

            if (!empty($indicators_db)) {

                foreach ($indicators_db as $id => $ind_db) {
                
                    $indicator_api = $this->_get_year_values($ind_db['i_code'],$year);

                    if (!empty($indicator_api)) {
                        foreach ($indicator_api as $ia => $ind_api) {
                            $params = array(
                                'i_id' => $ind_db['i_id'],
                                'id_value' => $ind_api['valor'],
                                'id_date' => date_format(date_create($ind_api['fecha']), "Y-m-d H:i:s"),
                                'id_add' => date("Y-m-d H:i:s"),
                            );
                            $create = $this->indicators_details_model->create($params);
                        }
                    }    
                    
                    $out[$year][] = $ind_db['i_code'];
                }                
                        
            }

            echo json_encode($out);
        }


        // get year values in the api
        private function _get_year_values($type, $year) {
            $api_url = 'https://mindicador.cl/api/'.$type.'/'.$year;
            if ( ini_get('allow_url_fopen') ) {
                $json = file_get_contents($api_url);
            } else {
                $curl = curl_init($api_url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $json = curl_exec($curl);
                curl_close($curl);
            }            
            $data = json_decode($json, true);
            $year_indicators = $data['serie'];

            return $year_indicators;
        }
        
        
    }
    
