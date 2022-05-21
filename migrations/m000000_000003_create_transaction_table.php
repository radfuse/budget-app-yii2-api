<?php

use yii\db\Migration;

/**
 * Handles the creation of table `transaction`.
 */
class m000000_000003_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transaction', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'category_id' => $this->integer(),
            'type' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'amount' => $this->float()->notNull(),
            'description' => $this->string(255),
            'transaction_date' => $this->date()->notNull(),
        ]);

        $this->addForeignKey('fk_transaction_user', 'transaction', 'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk_transaction_category', 'transaction', 'category_id', 'category', 'id', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('transaction');
    }
}
