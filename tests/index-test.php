<?php
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}

$defaultVendorPath = __DIR__ . '/../vendor/autoload.php';

defined('VENDOR_PATH') or define('VENDOR_PATH', getenv('VENDOR_PATH')?:$defaultVendorPath);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require(VENDOR_PATH . '/autoload.php');
require(VENDOR_PATH . '/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../src/config/test.php');
(new yii\web\Application($config))->run();