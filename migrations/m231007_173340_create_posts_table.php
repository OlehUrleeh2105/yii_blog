<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%posts}}`.
 */
class m231007_173340_create_posts_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'category' => $this->string(),
            'created_by' => $this->integer(),
        ]);

        // Add foreign key constraint if "created_by" references another table (e.g., user table).
        // $this->addForeignKey('fk-posts-created_by', '{{%posts}}', 'created_by', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop the table
        $this->dropTable('{{%posts}}');

        // If you added a foreign key constraint, you should also remove it here.
        // $this->dropForeignKey('fk-posts-created_by', '{{%posts}}');
    }
}
