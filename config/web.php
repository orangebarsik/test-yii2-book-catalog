<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'books-catalog',
	'language' => 'ru-RU',
	'name'=>'Каталог книг',
    'basePath' => dirname(__DIR__),
	'defaultRoute' => 'book/index',
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
		'@webroot' => dirname(dirname(__DIR__)) . '/web',
		'@web' => '/',
		'@uploads' => '@webroot/uploads',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'imBuFQVV2zf8FgKerJ1-6Cj8RBFJNvcf',
			'enableCsrfValidation' => true,
			'baseUrl' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning', 'info'],
					'categories' => ['sms-notification'],
					'logFile' => '@runtime/logs/sms.log', // отдельный файл для SMS
					'logVars' => [], // не добавлять переменные окружения
				],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,    // Включить ЧПУ
			'showScriptName' => false,    // Скрыть index.php
			'enableStrictParsing' => true, // Строгий разбор URL (опционально)
            'rules' => [
				// Главная страница
				'' => 'book/index',
				
				// Книги
				'books' => 'book/index',
				'book/create' => 'book/create',
				'book/<id:\d+>' => 'book/view',
				'book/<id:\d+>/update' => 'book/update',
				'book/<id:\d+>/delete' => 'book/delete',
				'book/<id:\d+>/delete-image' => 'book/delete-image',
				
				// Авторы
				'authors' => 'author/index',
				'author/create' => 'author/create',
				'author/<id:\d+>' => 'author/view',
				'author/<id:\d+>/update' => 'author/update',
				'author/<id:\d+>/delete' => 'author/delete',
				'author/<author_id:\d+>/subscribe' => 'author/subscribe',
				
				// Отчеты
				'reports/top-authors' => 'report/top-authors',
				'reports/top-authors/<year:\d{4}>' => 'report/top-authors',
				
				// Дополнительные правила
				'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['guest', 'user'],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
		'allowedIPs' => ['192.168.1.204', '::1'],
    ];
}

return $config;
