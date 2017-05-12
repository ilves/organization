<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

class RelationFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Relation';
    public $dataFile = __DIR__ . '/_relation-data.php';
}