<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m180926_211836_create_messages_votes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('messagesVotes', [
            'messageId' =>  $this->integer()->unsigned()->notNull(),
            'userId'    =>  $this->integer()->unsigned()->notNull(),
            'voteType'  =>  $this->string(1)->notNull(),
            'votedAt'   =>  $this->integer()->unsigned()->notNull()
        ]);

        $this->addPrimaryKey('mvPK', 'messagesVotes', ['messageId', 'userId']);

        $this->addForeignKey('fk-messages-messagesVotes', 'messagesVotes', 'messageId', 'messages', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-messages-messagesVotes', 'messagesVotes');

        $this->dropTable('messagesVotes');
    }
}
