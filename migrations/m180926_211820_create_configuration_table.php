<?php

use yii\db\Migration;

/**
 * Handles the creation of table `configuration`.
 */
class m180926_211820_create_configuration_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('configuration', [
            'name'  =>  $this->string(32)->notNull(),
            'value' =>  $this->text()
        ]);

        $this->addPrimaryKey('config_name', 'configuration', 'name');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('configuration');
    }
}
