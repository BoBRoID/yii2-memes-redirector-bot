<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m180926_211833_add_videoFileId_field_to_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('messages', 'videoFileId', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('messages', 'videoFileId');
    }
}
