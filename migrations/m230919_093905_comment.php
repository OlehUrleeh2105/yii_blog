<?php

use yii\db\Migration;

/**
 * Class m230919_093905_comment
 */
class m230919_093905_comment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('comments', [
            'id' => $this->primaryKey(10),
            'user_id' => $this->integer(10)->unsigned()->notNull(),
            'post_id' => $this->integer(10)->notNull(),
            'content' => $this->string(255)->notNull(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('comments');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230919_093905_comment cannot be reverted.\n";

        return false;
    }
    */
}
