<?php

use yii\db\Migration;

/**
 * Class m200628_185216_transactions
 */
class m200628_185216_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=MyISAM';
        }

        $this->createTable('transactions', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer(),
            'deposite_id' => $this->integer(),
            'type' => $this->tinyInteger(1)->comment("0-comission, 1-deposit"),
            'sum' => $this->decimal(10,2),
            'date_create' => $this->timestamp()->defaultExpression('current_timestamp'),
        ],$tableOptions);
        //$this->addCommentOnTable('transaction', 'Accounts transactions');
        // creates index for column `author_id`
        $this->createIndex(
            'idx-account-id',
            'transactions',
            'account_id'
        );
        $this->createIndex(
            'idx-deposite-id',
            'transactions',
            'deposite_id'
        );
        $this->createIndex(
            'idx-date-create',
            'transactions',
            'date_create'
        );
        $this->createIndex(
            'idx-type',
            'transactions',
            'type'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
       // echo "m200628_185216_transactions cannot be reverted.\n";

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-account-id',
            'transactions'
        );
        $this->dropIndex(
            'idx-deposite-id',
            'transactions'
        );
        $this->dropIndex(
            'idx-date-create',
            'transactions'
        );
        $this->dropIndex(
            'idx-type',
            'transactions'
        );
        $this->dropTable('transactions');

    }

}
