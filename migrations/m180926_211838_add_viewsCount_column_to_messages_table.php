<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m180926_211838_add_viewsCount_column_to_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('messages', 'viewsCount', $this->integer()->unsigned()->notNull()->defaultValue(0));
        $this->addColumn('messages', 'hasBeenSentAt', $this->integer()->unsigned()->null());

        $this->db->createCommand("UPDATE `messages` SET `hasBeenSentAt` = `created` WHERE `isSent` = '1'")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('messages', 'viewsCount');
        $this->dropColumn('messages', 'hasBeenSentAt');
    }
}
