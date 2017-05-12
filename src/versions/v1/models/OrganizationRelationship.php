<?php
/**
 * @copyright Copyright (c) 2017 Taavi Ilves
 */

namespace app\versions\v1\models;

use app\components\MappableQuery;
use app\models\Organization;
use yii\base\Model;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class for representing Organization relationship
 * @author Taavi Ilves <ilves.taavi@gmail.com>
 */
class OrganizationRelationship extends Model
{

    const CHILD = 'CHILD';
    const PARENT = 'PARENT';
    const SISTER = 'SISTER';

    /**
     * @var string Organization name
     */
    public $organizationName;

    /**
     * @var string type of the relationship
     */
    public $relationshipType;

    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'org_name' => 'organizationName',
            'relationship_type' => 'relationshipType'
        ];
    }

    /**
     * Method for creating query to find Organization relationships
     * @param Organization $organisation to find relationships for
     * @return Query
     */
    public static function findAllRelations(Organization $organisation)
    {
        $query = (new MappableQuery([
            'mapper' => function($item) {
                return self::createFromArray($item);
            }
        ]));

        return $query->select(['name', 'type'])
            ->from(['u' => $organisation->getParents()
                ->select(['name', new Expression('"'.self::PARENT.'" as type')])
            ->union($organisation->getChildren()
                ->select(['name', new Expression('"'.self::CHILD.'" as type')]), true)
            ->union($organisation->getSisters()
                ->select(['name',new Expression('"'.self::SISTER.'" as type')]))]);
    }

    /**
     * Method for creating OrganizationRelationship object from array
     * @param array $data containing object data
     * @return OrganizationRelationship
     */
    private static function createFromArray(array $data)
    {
        $model = new OrganizationRelationship();
        $model->organizationName = $data['name'];
        $model->relationshipType = $data['type'];
        return $model;
    }
}