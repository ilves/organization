<?php
/**
 * @copyright Copyright (c) 2017 Taavi Ilves
 */

namespace app\versions\v1\controllers;

use app\models\Organization;
use app\models\Relation;
use app\versions\v1\models\OrganizationRelationship;
use app\versions\v1\Module;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;

/**
 * OrganizationController implements organization related rest actions
 * @author Taavi Ilves <ilves.taavi@gmail.com>
 */
class OrganizationController extends Controller
{
    /**
     * @inheritdoc
     */
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

    /**
     * @inheritdoc
     */
    public function verbs()
    {
        return [
            'index'  => ['get'],
            'relationships' => ['get'],
            'clear' => ['delete'],
            'create' => ['post']
        ];
    }

    /**
     * Action for getting all Organizations
     * @return ActiveDataProvider
     */
    public function actionIndex()
    {
        return new ActiveDataProvider([
            'query' => Organization::find(),
            'pagination' => [
                'defaultPageSize' => Module::DEFAULT_PAGE_SIZE,
                'pageSizeLimit' => Module::DEFAULT_PAGE_SIZE_LIMIT,
            ],
        ]);
    }

    /**
     * Action for getting single Organization relationships
     * @param $name Organization name
     * @return array|ActiveDataProvider
     */
    public function actionRelationships($name)
    {
        $organisation = Organization::findByName($name);

        if (!$organisation) {
            return $this->errorResponse("Organization with name: $name not found", 404);
        }

        return new ActiveDataProvider([
            'query' => OrganizationRelationship::findAllRelations($organisation),
            'pagination' => [
                'defaultPageSize' => Module::DEFAULT_PAGE_SIZE,
                'pageSizeLimit' => Module::DEFAULT_PAGE_SIZE_LIMIT,
            ],
        ]);
    }

    /**
     * Action for inserting tree of Organizations and their relations
     * @return array
     */
    public function actionCreate()
    {
        $organizationsData = Yii::$app->request->getBodyParams();

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $this->insertOrganizations([$organizationsData], null);
            $transaction->commit();
        } catch (\Exception $exception) {
            $transaction->rollBack();
            return $this->errorResponse($exception->getMessage(), 403);
        }

        return ['data' => null];
    }

    /**
     * Action for clearing/deleting all Organizations and their relations
     * @return array
     */
    public function actionClear()
    {
        Yii::$app->db->createCommand()->truncateTable(Relation::tableName())->execute();
        Yii::$app->db->createCommand()->truncateTable(Organization::tableName())->execute();

        return ['data' => null];
    }

    /**
     * Method for rest api error response
     * @param string $message error message
     * @param int $statusCode response status code
     * @return array
     */
    protected function errorResponse($message, $statusCode)
    {
        Yii::$app->response->setStatusCode($statusCode);

        return [
            'errors' => [
                ['message' => $message],
            ],
        ];
    }

    /**
     * Method for inserting Organization and it's children into the database
     * @param Organization[] $organizations array of Organizations
     * @param Organization|null $parent parent organization
     * @throws \Exception thrown when input validation fails
     */
    protected function insertOrganizations(array $organizations, Organization $parent = null)
    {
        foreach ($organizations as $organization) {
            if (!isset($organization['org_name'])) {
                throw new \Exception('Organization name is required');
            }

            $name = $organization['org_name'];
            $organizationModel = Organization::findByName($name);

            if (!$organizationModel) {
                $organizationModel = new Organization();
                $organizationModel->name = $name;
                $organizationModel->save();
            }

            if ($parent) {
                if ($parent->id == $organizationModel->id) {
                    throw new \Exception('Organization can\'t be child of itself');
                }
                $relation = new Relation();
                $relation->organization_from_id = $parent->id;
                $relation->organization_to_id = $organizationModel->id;
                $relation->save();
            }

            if (isset($organization['daughters'])) {
                $this->insertOrganizations($organization['daughters'], $organizationModel);
            }
        }
    }

    public function actionTest()
    {
        $max = 2000000;
        $rows = [];
        $rows2 = [];

        $organizationModel = new Organization();
        $organizationModel->name = 'Parent 1';
        $organizationModel->save();

        $m = 0;
        for ($n = 0; $n < $max; $n++) {
            $rows[] = [
                'name' => 'Name ' . $n,
            ];
            $rows2[] = [
                'organization_from_id' => 1,
                'organization_to_id' => $n+2
            ];
            $m++;
            if ($m >= 10000) {
                Yii::$app->db->createCommand()->batchInsert(Organization::tableName(), ['name'], $rows)->execute();
                Yii::$app->db->createCommand()->batchInsert(Relation::tableName(), ['organization_from_id', 'organization_to_id'], $rows2)->execute();
                $rows = [];
                $rows2 = [];
                $m = 0;
            }
        }
    }
}