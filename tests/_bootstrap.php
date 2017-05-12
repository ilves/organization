<?php
$defaultVendorPath = __DIR__ . '/../vendor';

defined('VENDOR_PATH') or define('VENDOR_PATH', getenv('VENDOR_PATH')?:$defaultVendorPath);
define('YII_ENV', 'test');
defined('YII_DEBUG') or define('YII_DEBUG', true);

require(VENDOR_PATH . '/autoload.php');
require(VENDOR_PATH . '/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../src/config/test.php');
new yii\web\Application($config);

Yii::setAlias('@tests', __DIR__);