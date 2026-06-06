<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$login = getenv('MAILEXAM_LOGIN') ?: '';
$port = (int) (getenv('MAILEXAM_PORT') ?: 587);

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'container' => [
        'singletons' => [
            \yii\mail\MailerInterface::class => [
                'class' => \yii\symfonymailer\Mailer::class,
                'useFileTransport' => false,
                'viewPath' => '@app/mail',
                'transport' => [
                    'scheme' => 'smtp',
                    'host' => $login . '.mailexam.io',
                    'username' => $login,
                    'password' => getenv('MAILEXAM_PASSWORD') ?: '',
                    'port' => $port,
                ],
            ],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'shYytrGzH7R6_MVdl_O2HcNrK9Kvq3w8',
            'parsers' => [
                'application/json' => \yii\web\JsonParser::class,
            ],
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'user' => [
            'identityClass' => \app\models\User::class,
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => \yii\mail\MailerInterface::class,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'POST mail/test' => 'mail/test',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
    ];
}

return $config;
