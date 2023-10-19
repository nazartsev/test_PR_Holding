<?php

use yii\db\Migration;

/**
 * Class m231019_175550_add_table_for_apple
 */
class m231019_175550_add_table_for_apple extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%apple}}', [
            'id' => $this->primaryKey(),
            'color' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'dropped_at' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'eat_percent' => $this->integer()->notNull()->defaultValue(0),
            'is_hidden' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%apple}}');
    }
}
