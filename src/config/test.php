<?php

$params = require(__DIR__ . '/params.php');
$dbParams = require(__DIR__ . '/db-test.php');

return [
    'id' => 'basic-tests',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['v1'],
    'language' => 'en-US',
    'modules' => [
        'v1' => [
            'class' => 'app\versions\v1\Module',
        ],
    ],
    'components' => [
        'response' => [
            'class' => 'app\components\RestResponse',
        ],
        'db' => $dbParams,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'mailer' => [
            'useFileTransport' => true,
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../html/assets',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],
    'params' => $params,
];