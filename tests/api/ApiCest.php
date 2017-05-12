<?php
class ApiCest
{
    public function getUndefinedUrl(\ApiTester $I)
    {
        $I->sendGET('/random');
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND);
        $I->seeResponseJsonMatchesJsonPath("$.errors[0].message");
    }
}