<?php

return CMap::mergeArray(
	require(dirname(__FILE__).'/main.php'),
	array(
		'components'=>array(
			'fixture'=>array(
				'class'=>'system.test.CDbFixtureManager',
			),
			'httpsqs' => array(
        		'class'=> 'Httpsqs',
        		'httpsqs_host' => '127.0.0.1',
        		'httpsqs_port' => '3309',
        		'httpsqs_auth' => 'We1qupa!'
        	),
			'db'=>array(
				'connectionString'=>'mysql:host=rdsvzuubavebvzi.mysql.rds.aliyuncs.com;dbname=vqupai_test',
			),
		),
	)
);
