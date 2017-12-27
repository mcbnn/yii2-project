<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@dektrium/user/views/security' => '@app/views/site'
                ],
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => 'BpB9C1BouXWz2aezkOQb2Q',
                    'consumerSecret' => 'qSD5UwEkZoJF3suoxahH8UYcxCfBcVitVIdUhOBRs',
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'X5_tDxAdH8cGKL_Fu6myJwM4ge8ObT2D',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
//        'user' => [
//            'identityClass' => 'app\models\User',
//            'enableAutoLogin' => true,
//        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
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
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'rules' => [
            ],
        ],

    ],
    'modules' => [
        'rbac' => [
            'class' => 'dektrium\rbac\RbacWebModule',
            'admins' => ['admin@admin.ru'],
        ],
        'user' => [
            'adminPermission' => 'admin',
            'class' => 'dektrium\user\Module',
            'enableConfirmation' => false,
            'admins' => ['admin'],
            'enableUnconfirmedLogin' => true,
            'enableFlashMessages' => true,
            'confirmWithin' => 21600,
            'controllerMap' => [
                'registration' => [
                    'class' => 'app\controllers\_RegistrationController',
                    'on ' . \dektrium\user\controllers\RegistrationController::EVENT_AFTER_REGISTER => function ($e) {
                        $auth = Yii::$app->authManager;
                        $rolesObj = array_keys(Yii::$app->authManager->getRoles());
                        $roleName = $rolesObj[$e->form->roles];
                        $role = $auth->getRole($roleName);
                        $role->ruleName = $roleName;
                        $user = \dektrium\user\models\User::findOne(['username' => $e->form->username]);
                        $auth->assign($role, $user->id);
                    }
                ],
            ],
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
    ];
}

return $config;
