<?php

	// access local
	$config['access_default'] = array(
		'servername' => 'localhost',
		'username'	 => 'root',
		'password'	 => '',
		'database'	 => 'economic_indicators',
		'httphost'	 => 'localhost/ieconomic_v2/dist',
		'status'	 => true
	);

	define("ACCESS_DEFAULT", $config['access_default']);


	// access in production
	$config['access_production'] = array(
		'servername' => 'localhost',
		'username'	 => 'scarletv_root',
		'password'	 => '*App*Indicadores123',
		'database'	 => 'scarletv_economic_indicators',
		'httphost'	 => 'ieconomic.scarletvillasana.com',
		'status'	 => false
	);

	define("ACCESS_PRODUCTION", $config['access_production']);
	
