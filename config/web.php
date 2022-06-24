<?php
/* Include debug functions */
require_once(__DIR__ . '/functions.php');

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Case FIEP',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        // 'request' => [
        //     // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        //     'cookieValidationKey' => 'IRKFwUr47GNLGCAU3zfQdJHk3m2OSOwT',
        // ],
        'request' => [
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
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
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                // ...
                // will handle `gii/default/login` uri and makes infinite redirection loop circle
                'gii/<controller:\w+>/<action:[\w-]+>' => 'gii/<controller>/<action>',
                '' => 'site/index',
                '<controller:\w+>/<action:\w+>/<id:\w+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                // ...
            ],
        ],
        'formatter' => [
            'dateFormat' => 'php:d/m/Y',
            'datetimeFormat' => 'd/M/Y H:m:s',
            'decimalSeparator' => ',',
            'thousandSeparator' => '.',
            'currencyCode' => 'R$',
            'locale' => 'pt_br',
            'defaultTimeZone' => 'America/Sao_Paulo',
            'class' => 'yii\i18n\Formatter',

        ],
    ],
    'params' => $params,
    'sourceLanguage' => 'pt-BR',
    'language' => 'pt-BR',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        // 'allowedIPs' => ['127.0.0.1', '::1', $_SERVER['REMOTE_ADDR']],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', $_SERVER['REMOTE_ADDR']]
        // uncomment the following to add your IP if you are not connecting from localhost.
        // 'allowedIPs' => ['127.0.0.1', '::1', $_SERVER['REMOTE_ADDR']],
    ];
    $config['modules']['gii']['generators']['migration'] = [
        'class' => \ymaker\gii\migration\Generator::class,
    ];
}

return $config;
