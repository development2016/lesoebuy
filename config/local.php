<?php
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/local_db.php');

$config = [
    'id' => 'E-Procument',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        // ...
        'mongo-gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'mongoDbModel' => [
                    'class' => 'yii\mongodb\gii\model\Generator'
                ]
            ],
        ],


    ],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js'=>[]
                ],
                'meysampg\gmap\GMapAsset' => [
                    'key' => 'AIzaSyAyNW-6GSMb1J1ZNV_bkdwbFqvkNCN3CRE',
                    'language' => 'en'
                ],
        
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'nV8CT6N4AFgSfzVDoJPWEptAifJWK7Xh',
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
            'viewPath' => '@app/mail',
            'useFileTransport' => false,
            'transport' => [
            'class' => 'Swift_SmtpTransport',
            'host' => 'mail.lesoebuy.com',
            'username' => 'noreply@lesoebuy.com',
            'password' => 'Amtujpino.1',
            'port' => '587',
            'encryption' => 'tls',
            'streamOptions' => [
                    'ssl' => [
                        'verify_peer' => false, // is used to for the verification of SSL certificate used.
                        'allow_self_signed' => true, // http://php.net/manual/en/context.ssl.php
                        'verify_peer_name' => false, // is used for verification of peer name.
                    ],
                ],
                
            ],
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
        'mongo' => require(__DIR__ . '/local_db.php'),
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=procument',
            'username' => 'root',
            'password' => 'secret',
            'charset' => 'utf8',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        
    ],
    'params' => $params,
];

return $config;