<?php
$defaultVendorPath = __DIR__ . '/../../vendor';

defined('VENDOR_PATH') or define('VENDOR_PATH', getenv('VENDOR_PATH')?:$defaultVendorPath);
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'production');

/** @noinspection PhpIncludeInspection */
require(VENDOR_PATH . '/autoload.php');
/** @noinspection PhpIncludeInspection */
require(VENDOR_PATH . '/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
(new yii\web\Application($config))->run();