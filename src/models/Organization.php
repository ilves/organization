<?php
/**
 * @copyright Copyright (c) 2017 Taavi Ilves
 */

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Organization database model
 * @property integer id
 * @property string name
 * @author Taavi Ilves <ilves.taavi@gmail.com>
 */
class Organization extends ActiveRecord
{
    /** @inheritdoc */
    public static function tableName()
    {
        return 'organization';
    }

    /** @inheritdoc */
    public function fields()
    {
        return [
            'id',
            'org_name' => 'name',
        ];
    }

    /**
     * Finds Organization by provided name
     * @param string $name Organization name
     * @return Organization that matched name or 'null' if not found
     */
    public static function findByName($name)
    {
        return self::findOne(['name' => $name]);
    }

    /**
     * Organization children query
     * @return ActiveQuery
     */
    public function getChildren()
    {
        return self::find()
            ->leftJoin(Relation::tableName(), 'organization_to_id = id')
            ->andWhere(['organization_from_id' => $this->id]);
    }

    /**
     * Organization parents query
     * @return ActiveQuery
     */
    public function getParents()
    {
        return self::find()
            ->leftJoin(Relation::tableName(), 'organization_from_id = id')
            ->andWhere(['organization_to_id' => $this->id]);
    }

    /**
     * Organization sisters query
     * @return ActiveQuery
     */
    public function getSisters()
    {
        return self::find()
            ->leftJoin(Relation::tableName() . ' b', 'b.organization_to_id = id')
            ->leftJoin(Relation::tableName() . ' a', 'a.organization_from_id = b.organization_from_id')
            ->andWhere('a.organization_to_id = :id AND id != :id', [
                ':id' => $this->id,
            ]);
    }
}