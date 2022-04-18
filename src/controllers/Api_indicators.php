<?php

    class Api_indicators {


        function __construct() {
            $this->indicators_model = new Ei_indicators_model();
            $this->indicators_details_model = new Ei_indicators_details_model();
        }


        public function list(){

            $datos_post = json_decode(file_get_contents('php://input'), true);
            $main = null;
            $daily = null;
            $all = null;

            if (!empty($datos_post)) {

                $params = array(
                    'year' => $datos_post['year'],
                    'month' => $datos_post['month'],
                    'current_week' => $datos_post['current_week']
                );
                
                $data_all = $this->indicators_model->get_by_params($params);                

                if (!empty($data_all)) {  
                    
                    $date = new DateTime();
                    $now = date_format($date,"Y-m-d H:i:s");

                    foreach ($data_all as $key => $value) {
                        $all[$value['i_code']]['i_id'] = $value['i_id'];
                        $all[$value['i_code']]['i_code'] = $value['i_code'];
                        $all[$value['i_code']]['i_name'] = $value['i_name'];
                        $all[$value['i_code']]['i_unit_measure'] = $value['i_unit_measure'];
                        $all[$value['i_code']]['i_currency_symbol'] = CURRENCY_SYMBOLS[$value['i_unit_measure']];
                        $all[$value['i_code']]['i_description'] = $value['i_description'];
                        $all[$value['i_code']]['i_status'] = $value['i_status'];
                        $all[$value['i_code']]['detail'][$key]['id_value'] = $value['id_value'];
                        $all[$value['i_code']]['detail'][$key]['id_value_format'] = number_format($value['id_value'], 2, ',', '.');
                        $all[$value['i_code']]['detail'][$key]['id_date'] = $value['id_date'];
                        $all[$value['i_code']]['detail'][$key]['id_date_format'] = date_format(date_create($value['id_date']),"d/m/Y H:i a");
                        $all[$value['i_code']]['detail'][$key]['id_date_format_chart'] = date_format(date_create($value['id_date']),"d/m");
                    }
                }

                $daily = $this->indicators_model->get_by_params(array('more_recent'=>true),true,'id.i_id');

                if(!empty($daily)){
                    foreach ($daily as $k => $val) {
                        $daily[$k]['i_currency_symbol'] = CURRENCY_SYMBOLS[$val['i_unit_measure']];
                        $daily[$k]['id_value'] = number_format($val['id_value'], 2, ',', '.');
                        $daily[$k]['id_date_format'] = date_format(date_create($val['id_date']),"d/m/Y H:i a");
                        if ($val['i_code']=='uf' || $val['i_code']=='dolar' || $val['i_code']=='euro') {
                            $main[$k] = $daily[$k];
                        }
                    }
                }
            }

            $out = ['all'=>$all, 'daily'=>$daily, 'main'=>$main];
            //$out = ['h'=>'aaa'];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($out);
        }
        
    }