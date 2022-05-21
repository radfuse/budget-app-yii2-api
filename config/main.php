<?php

$params = require(__DIR__ . '/params.php');
$rules = require(__DIR__ . '/rest-rules.php');

return [
    'id' => 'budget-api',
    'basePath' => dirname(__DIR__),    
    'bootstrap' => ['log'],
    'components' => [
		'response' => [
			'format' =>  \yii\web\Response::FORMAT_JSON
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableSession' => false,
            'loginUrl' => null,
        ],
		'request' => [
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
            ],
            'enableCookieValidation' => false,
		],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
			'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => $rules,        
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => env('DB_DSN'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key' => env('JWT_KEY'),
        ],
    ],
    'params' => $params,
];



