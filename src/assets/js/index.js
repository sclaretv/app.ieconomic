var myApp = angular.module('myApp',['angular-toArrayFilter']);



myApp.controller('economic_indicators', ['$scope', '$http', '$filter', '$timeout', function($scope, $http, $filter, $timeout) {

    var date_now = new Date();
    $scope.this_month = date_now.getMonth()+1;
    $scope.this_year = date_now.getFullYear();
    
    $scope.main = [];
    $scope.daily = [];
    $scope.all = [];

    $scope.filter_indicator = '';
    $scope.indicator_selected = '';
    $scope.month_selected = {};

    $scope.mode = 'chart';
    $scope.detail_chart = null;
    $scope.detail_table = null;

    $scope.cargando = false;

    $scope.years = [2021,2022];

    $scope.months = [
        {'month_id':1,'month_label':'Ene'},{'month_id':2,'month_label':'Feb'},{'month_id':3,'month_label':'Mar'},{'month_id':4,'month_label':'Abr'},
        {'month_id':5,'month_label':'May'},{'month_id':6,'month_label':'Jun'},{'month_id':7,'month_label':'Jul'},{'month_id':8,'month_label':'Ago'},
        {'month_id':9,'month_label':'Sep'},{'month_id':10,'month_label':'Oct'},{'month_id':11,'month_label':'Nov'},{'month_id':12,'month_label':'Dic'},
    ]

    $scope.search = {'year':$scope.this_year, 'month':$scope.this_month, 'current_week':1};           
    

    $scope.getIndicators = function() {
        $scope.main = [];
        $scope.daily = [];
        $scope.all = [];
        $scope.mode = 'chart';
        $scope.detail_chart = null;
        $scope.detail_table = null;

        if($scope.search.year!=null){
            $scope.cargando = true;
            $http.post('api_indicators/list',$scope.search).then(function(data) {  
                
                if(data.data.main!=null || data.data.daily!=null || data.data.all!=null){
                    $scope.formatData(data.data);                    
                }
                $timeout(function(){$scope.cargando=false;},1000);
            });
        }        
    }


    $scope.formatData = function(data){        
        $scope.main = angular.copy(data.main);
        $scope.daily = angular.copy(data.daily);     
        $scope.all = angular.copy(data.all);    

        // principales: uf, dolar y euro
        if($scope.main!=null){
            angular.forEach($scope.main, function(ind, key) {
                if(ind.i_code=='uf'){
                    ind.bg_color = 'rgba(198,112,0,1)';
                    ind.box_shadow = '0px 10px 20px rgba(198,112,0,.30)';
                    ind.icon_class = 'bi bi-currency-exchange';
                }
                if(ind.i_code=='dolar'){
                    ind.bg_color = 'rgba(98,126,235,1)';
                    ind.box_shadow = '0px 10px 20px rgba(98,126,235,.30)';
                    ind.icon_class = 'bi bi-currency-dollar';
                }
                if(ind.i_code=='euro'){
                    ind.bg_color = 'rgba(0,164,194,1)';
                    ind.box_shadow = '0px 10px 20px rgba(0,164,194,.30)';
                    ind.icon_class = 'bi bi-currency-euro';
                }
            });
        }        

        $scope.filterDetail('uf');
        
    }    


    $scope.filterDetail = function(i_code){  
        
        $scope.indicator_selected = i_code;
        $scope.detail_table = ($scope.all!=null)?angular.copy($scope.all[i_code]):null;
        $scope.detail_chart = ($scope.all!=null)?angular.copy($scope.all[i_code]):null;        

        if ($scope.detail_chart!=null) {
            
            $scope.detail_chart.id_dates = [];
            $scope.detail_chart.id_values = [];

            angular.forEach($scope.detail_chart.detail, function(detail) {
                $scope.detail_chart.id_dates.unshift(detail.id_date_format_chart);
                $scope.detail_chart.id_values.unshift(detail.id_value);
            });

            $scope.graphDetail($scope.detail_chart);
        }

        //$scope.calculateVarAverage();
    }


    // chart.js
    var myChart = null;
    $scope.labels = [];
    $scope.data = [];

    $scope.graphDetail = function(detail){
        $scope.labels = detail.id_dates;
        $scope.data = detail.id_values;
        if(myChart!=null){
            addData(detail.i_name);
        } else {
            $scope.displayGraph(detail.i_name);
        }
    }

    function addData(i_name) {
        myChart.data.labels= $scope.labels;
        myChart.data.datasets.forEach((dataset) => {
            dataset.label = i_name;
            dataset.data = $scope.data;
        });
        myChart.update();
    }

    $scope.displayGraph = function(i_name){    
        var ctx = document.getElementById('myChart');

        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: $scope.labels,
                datasets: [{
                    label: i_name,
                    backgroundColor: '#EDEEFF',
                    borderColor: '#627EEB',
                    color: '#adb5bd',
                    fill: 'start',
                    data: $scope.data,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    line: {
                        tension: 0.4,
                        borderWidth: 3,
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Fecha',
                            color: '#6c757d',
                            font: {
                            size: 13,
                            },
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Valor',
                            color: '#6c757d',
                            font: {
                            size: 13,
                            },
                        }
                    }
                }
            }
        });
    } 


    $scope.getIndicators();

    $scope.$watch('search.year', function() {
        $scope.search.current_week = ($scope.search.year!=$scope.this_year || $scope.search.month!=$scope.this_month)?0:1;
    });

    $scope.$watch('search.month', function() {
        $scope.search.current_week = ($scope.search.year!=$scope.this_year || $scope.search.month!=$scope.this_month)?0:1;
    });

    $scope.change_currentweek = function() {
        $scope.search.year = ($scope.search.year!=$scope.this_year && $scope.search.current_week)?$scope.this_year:$scope.search.year;
        $scope.search.month = ($scope.search.month!=$scope.this_month && $scope.search.current_week)?$scope.this_month:$scope.search.month;
    };


}]);
 