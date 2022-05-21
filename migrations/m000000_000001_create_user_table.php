<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m000000_000001_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'auth_key' => $this->string(255)->notNull(),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'last_login_at' => $this->integer(11),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
