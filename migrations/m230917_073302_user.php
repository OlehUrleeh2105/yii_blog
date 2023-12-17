<?php

use yii\db\Migration;

/**
 * Class m230917_073302_user
 */
class m230917_073302_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull()->unique(),
            'password' => $this->string()->notNull(),
            'name' => $this->string()->notNull(),
            'surname' => $this->string()->unique(),
            'is_admin' => $this->boolean()->defaultValue(false),
        ]);        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230917_073302_user cannot be reverted.\n";

        return false;
    }
    */
}
