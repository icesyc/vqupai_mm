<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');
Yii::setPathOfAlias('highcharts', dirname(__FILE__).'/../extensions/yii-highcharts-3.0.9/highcharts');

return array(
	'timeZone' => 'Asia/Shanghai',
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=> '微趣拍',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.widgets.*',
	),
	
	'language' => 'zh_CN',
	'defaultController' => 'none',

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'format',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1', '220.181.11.232','222.128.128.160'),//,'221.223.88.238'),
			'generatorPaths'=>array(
                'bootstrap.gii',
            ),
		),
	),

	// application components
	'components'=>array(
		'format' => array(
			'dateFormat' => 'Y-m-d',
			'datetimeFormat' => 'Y-m-d H:i:s',
		),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		'bootstrap'=>array(
            'class'=>'bootstrap.components.Bootstrap',
        ),
		'httpsqs' => array(
        	'class'=> 'Httpsqs',
        	'httpsqs_host' => '127.0.0.1',
        	'httpsqs_port' => '3310',
        	'httpsqs_auth' => 'We1qupa!'
        ),
		// uncomment the following to enable URLs in path-format
		/*
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		*/
		'db'=>array(
			'connectionString' => 'mysql:host=rdsbyqirjbyqirj.mysql.rds.aliyuncs.com;dbname=vqupai',
			'emulatePrepare' => true,
			'username' => 'ilovevqp',
			'password' => 'h0wmuchdoyoulove',
			'charset' => 'utf8',
			'enableParamLogging' => true,
			'schemaCachingDuration' => 2592000,
		),
		'cache'=>array(
            'class'=>'CFileCache',
        ),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'index/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				//*
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'info, trace, error, warning',
					'maxLogFiles'=>'1000',
					'maxFileSize'=>102400,
					'rotateByCopy'=>true,
					'filter'=>array(
                                'class'=>'CLogFilter',
                                'logUser'=>false,
                                'prefixUser'=>true,
                                'prefixSession'=>false,
                                'logVars' =>array(array('_SERVER','REMOTE_ADDR'),
                                        //array('_SERVER', 'REDIRECT_URL'),
                                        '_GET',
                                ),
                        ),
					//'categories'=>'access, system.db.CDBCommand',
				),
				array(
					'class'=>'CWebLogRoute',
					//'enabled'=>true,
					'enabled'=>false,
					//'categories'=>'system.db.CDBCommand',
					//'levels'=>'error,warning',
					//'categories'=>'system.db.*',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'admin@vqupai.com',
		'resumeUploadPath'=>'resume',
	),
);
