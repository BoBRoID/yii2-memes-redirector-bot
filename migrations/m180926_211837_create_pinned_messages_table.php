<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m180926_211837_create_pinned_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('pinnedMessages', [
            'id'                =>  $this->primaryKey()->unsigned(),
            'messageId'         =>  $this->integer()->unsigned()->notNull(),
            'addedAt'           =>  $this->integer()->unsigned()->notNull(),
            'pinnedAt'          =>  $this->integer()->unsigned()->null(),
            'unpinnedAt'        =>  $this->integer()->unsigned()->null(),
            'pinFrom'           =>  $this->dateTime()->notNull(),
            'pinTo'             =>  $this->dateTime()->notNull(),
            'isDeleted'         =>  $this->boolean()->unsigned()->notNull()->defaultValue(0),
            'removeAfterUnpin'  =>  $this->boolean()->unsigned()->notNull()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('pinnedMessages');
    }
}
