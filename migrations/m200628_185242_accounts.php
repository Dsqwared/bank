<?php

use yii\db\Migration;

/**
 * Class m200628_185242_accounts
 */
class m200628_185242_accounts extends Migration
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
        $this->createTable('accounts', [
            'id' => $this->primaryKey(),
            'inn' => $this->string(10)->notNull()->unique(),
            'access' => $this->tinyInteger(1)->defaultValue(0)->comment("1-admin, 0-client"),
            'first_name' => $this->string(255)->notNull()->unique(),
            'last_name' => $this->string(255)->notNull(),
            'password_hash' =>$this->string(255)->notNull(),
            'auth_key' =>$this->string(32)->notNull(),
            'gender' => $this->tinyInteger(1)->comment("0-female, 1-male"),
            'date_of_birth' => $this->DATE()->defaultValue(NULL),
            'date_create' => $this->DATE()->defaultExpression('CURDATE()'),
        ], $tableOptions);
       // $this->addCommentOnTable('accounts', 'Accounts');

        $this->insert('accounts', [
            'inn' => '1111111111',
            'first_name' => 'admin',
            'access' => '1',
            'last_name' => 'admin',
            'gender' => '1',
            'password_hash' => '$2y$13$O181FKYztApvY2PbEXnW6O7vMnoP9kigqHEBx6O/3Cj3qaAhRa65q', //111111
            'auth_key' => 'sVpeiJ4GvqJr34vFyXpTtS4mI-S_5_jO',
            'date_of_birth' => '1980-01-01',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('accounts');
    }

    public function isAdmin()
    {
        return \Yii::$app->user->identity->access;
    }

}
