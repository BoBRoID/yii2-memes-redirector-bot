<?php

use yii\db\Migration;

/**
 * Handles the creation of table `messages`.
 */
class m180926_211834_add_columns_to_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $int = $this->integer()->unsigned()->notNull()->defaultValue(0);

        $this->addColumn('messages', 'likesCount', $int);
        $this->addColumn('messages', 'dislikesCount', $int);
        $this->addColumn('messages', 'useKeyboardId', $int);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('messages', 'likesCount');
        $this->dropColumn('messages', 'dislikesCount');
        $this->dropColumn('messages', 'useKeyboardId');
    }
}
