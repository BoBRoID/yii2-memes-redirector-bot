<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m180926_211835_add_posted_id_column_to_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('messages', 'postedMessageId', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('messages', 'postedMessageId');
    }
}
