<?php

use yii\db\Migration;

/**
 * Class m200628_202735_deposits
 */
class m200628_202735_deposits extends Migration
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

        $this->createTable('deposits', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer(),
            'deposit' => $this->decimal(12,2),
            'percent' => $this->decimal(4,2),
            'date_create' => $this->DATE()->defaultExpression('CURDATE()'),
        ],$tableOptions);
           $this->createIndex(
            'idx-account-id',
            'deposits',
            'account_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        //echo "m200628_202735_deposits cannot be reverted.\n";

        // drops index for column `account_id`
        $this->dropIndex(
            'idx-account-id',
            'deposits'
        );

        $this->dropTable('deposits');
    }


}
