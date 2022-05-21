<?php

return [
	'POST,OPTIONS login' => 'auth/login',
	'POST,OPTIONS register' => 'auth/register',
	'OPTIONS statistics' => 'statistics/options',
	'GET statistics' => 'statistics/index',
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => "category",
		'pluralize' => false,
	],
	[
		'class' => 'yii\rest\UrlRule',
		'controller' => "transaction",
		'pluralize' => false,
	],
];