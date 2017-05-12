<?php
class OrganizationCest
{
    public function postOrganizations(\ApiTester $I)
    {
        $I->sendPOST("/organizations", [
            'org_name' => 'First',
            'daughters' => [
                [
                    'org_name' => 'First daughter',
                ],
                [
                    'org_name' => 'Second daughter',
                ]
            ]
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.data');
    }

    public function postOrganizationsWithMissingNameFails(\ApiTester $I)
    {
        $I->sendPOST("/organizations", [
            'org_name' => 'First',
            'daughters' => [
                [
                    'org_name:' => 'Second daughter',
                ],
                [
                    'org_name' => 'Second daughter',
                ]
            ]
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.errors');
    }

    public function postOrganizationItselfChildFails(\ApiTester $I)
    {
        $I->sendPOST("/organizations", [
            'org_name' => 'First',
            'daughters' => [
                [
                    'org_name:' => 'First',
                ],
                [
                    'org_name' => 'Second daughter',
                ]
            ]
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::FORBIDDEN);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.errors');
    }

    public function getOrganizationRelations(\ApiTester $I)
    {
        $I->sendGET("/organization/relationships", [
            'name' => 'Org 1 child 1'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath("$.data.[0].org_name");
        $I->seeResponseJsonMatchesJsonPath("$.data.[0].relationship_type");
    }

    public function getOrganizationRelationsNameNotFound(\ApiTester $I)
    {
        $I->sendGET("/organization/relationships", [
            'name' => 'Org 1 child 100'
        ]);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath("$.errors[0].message");
    }

    public function clearOrganizations(\ApiTester $I)
    {
        $I->sendDELETE("/organizations");
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.data');
        $I->assertSame((int)\app\models\Organization::find()->count(), 0);
        $I->assertSame((int)\app\models\Relation::find()->count(), 0);
    }

    public function unsupportedMethodCallShouldReturnError(\ApiTester $I)
    {
        $I->sendPUT('/organizations');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND);
        $I->seeResponseJsonMatchesJsonPath("$.errors[0].message");
    }
}