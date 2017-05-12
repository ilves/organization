<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

class OrganizationFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Organization';
    public $dataFile = __DIR__ . '/_organization-data.php';
}