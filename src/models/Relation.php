<?php
/**
 * @copyright Copyright (c) 2017 Taavi Ilves
 */

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Relation between two organizations
 * @property integer organization_from_id
 * @property integer organization_to_id
 * @author Taavi Ilves <ilves.taavi@gmail.com>
 */
class Relation extends ActiveRecord
{
    /** @inheritdoc */
    public static function tableName()
    {
        return 'organization_relation';
    }
}