<?php

	// access local
	$config['access_default'] = array(
		'servername' => 'localhost',
		'username'	 => 'root',
		'password'	 => '',
		'database'	 => 'economic_indicators',
		'status'	 => true
	);

	define("ACCESS_DEFAULT", $config['access_default']);


	// access in production
	$config['access_production'] = array(
		'servername' => '51.222.153.62',
		'username'	 => 'scarletv_root',
		'password'	 => '*App*Indicadores123',
		'database'	 => 'scarletv_economic_indicators',
		'status'	 => false
	);

	define("ACCESS_PRODUCTION", $config['access_production']);
	
?>