<?php
/**
 * @copyright Copyright (c) 2017 Taavi Ilves
 */

use yii\db\Migration;
use yii\db\Schema;

class m170506_190631_init extends Migration
{
    const TABLE_ORG = 'organization';
    const TABLE_REL = 'organization_relation';

    public function safeUp()
    {
        $this->createTable(self::TABLE_ORG, [
            'id' => Schema::TYPE_UPK,
            'name' => Schema::TYPE_STRING . ' NOT NULL',
        ]);

        $this->createIndex('name__AK', 'organization', ['name',], true);

        $this->createTable(self::TABLE_REL, [
            'organization_from_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'organization_to_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL'
        ]);

        $this->createIndex('PRIMARY', 'organization_relation', [
            'organization_from_id',
            'organization_to_id'
        ], true);

        $this->createIndex('organization_to_id__IX', 'organization_relation','organization_to_id');

        $this->addForeignKey('organization_from_id__FK', self::TABLE_REL, 'organization_from_id',
            self::TABLE_ORG, 'id');
        $this->addForeignKey('organization_to_id__FK', self::TABLE_REL, 'organization_to_id',
            self::TABLE_ORG, 'id');

    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_REL);
        $this->dropTable(self::TABLE_ORG);
    }
}