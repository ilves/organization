<?php

namespace tests\_support;

use Codeception\Module;
use tests\fixtures\OrganizationFixture;
use tests\fixtures\RelationFixture;
use yii\test\FixtureTrait;
use yii\test\InitDbFixture;

class FixtureHelper extends Module
{
    use FixtureTrait {
        loadFixtures as public;
        fixtures as public;
        globalFixtures as public;
        createFixtures as public;
        unloadFixtures as protected;
        getFixtures as protected;
        getFixture as protected;
    }

    /**
     * Method called before any suite tests run. Loads User fixture login user
     * to use in acceptance and functional tests.
     * @param array $settings
     */
    public function _beforeSuite($settings = [])
    {
        $this->loadFixtures();
    }

    /**
     * Method is called after all suite tests run
     */
    public function _afterSuite()
    {
        $this->unloadFixtures();
    }

    /**
     * @inheritdoc
     */
    public function fixtures()
    {
        return [
            OrganizationFixture::className(),
            RelationFixture::className()
        ];
    }
}