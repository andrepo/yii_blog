<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'xo_news',
    'name' => 'Crossover News',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '0IPoa6Wxzlj7y9J758kT27mb0dGUpOxY',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            // Defines SEO friendly URLs ...
            'rules' => [
                // Articles
                //'articles' => 'article/index',
                'articles/rss' => 'article/rss',
                'articles/user/<user_id:\d+>' => 'article/userarticles',
                'article/<id:\d+>/<title>'=>'article/view',
                'article/<action:(create|delete|export)>/<id:\d+>' => 'article/<action>',
                // User
                // ... registration
                'register' => 'user/create',
                'user/activate/<token:.*>' => 'user/activate',
                'user/resend-activation-email' => 'user/resend',
                // ... profile
                'user/<action:(view|edit)>/<id:\d+>' => 'user/<action>',
                // ... password
                'user/set-password' => 'user/setpassword',
                'user/forgot-password' => 'user/forgotpassword',
                'user/reset-password/<token:.*>' => 'user/resetpassword',
                // Site
                '<action:(index|login|logout|validate)>' => 'site/<action>',
            ],
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
            // Using PHP Mailer as requested:
            'class'            => 'zyx\phpmailer\Mailer',
            'viewPath'         => '@app/mail',
            'useFileTransport' => false,
            'config'           => [
                'host' => 'localhost',
                'port' => '25',
            ],
            /*
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
                'port' => '25',
            ],
            */
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '192.168.11.*'] // retricted to IPs on local network for security reasons
    ];
}

return $config;
