<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m180926_211832_create_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('messages', [
            'id'                =>  $this->primaryKey(),
            'created'           =>  $this->integer()->unsigned()->notNull(),
            'isSent'            =>  $this->boolean()->unsigned()->defaultValue(0),
            'messageId'         =>  $this->integer(),
            'text'              =>  $this->text(),
            'photoFileId'       =>  $this->text(),
            'animationFileId'   =>  $this->text(),
            'audioFileId'       =>  $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('messages');
    }
}
