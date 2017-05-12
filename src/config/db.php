<?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host='.getenv('DB_HOST').':3306;dbname='.getenv('DB_DATABASE'),
    'username' => getenv('DB_USER'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8',
];