<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property int|null $account_id
 * @property int|null $type
 * @property float|null $sum
 * @property string $date_create
 */
class Transactions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id', 'type'], 'integer'],
            [['sum'], 'number'],
            [['date_create'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'type' => 'Type',
            'sum' => 'Sum',
            'date_create' => 'Date Create',
        ];
    }

   public function addTransaction($data,$addVal,$today){
        $this->account_id = $data['account_id'];
        $this->deposite_id = $data['id'];
        $this->type = $data['type'];
        $this->sum = $addVal;
        $this->date_create = $today;
        $this->save();
    }

    public static function getReportProfit(){
        $dep = self::find()->alias('t')->select(["CONCAT(YEAR(t.date_create),'-', MONTH(t.date_create)) month", ' SUM(IF(type = 0, sum, 0)) - SUM(IF(type = 1, sum, 0)) bankProfit'])
        ->groupBy(['YEAR(t.date_create)', 'MONTH(t.date_create)'])
            ->orderBy([
                'YEAR(t.date_create)' => SORT_DESC,
                'MONTH(t.date_create)'=>SORT_DESC
            ])
        ->asArray();
        return $dep->all();
    }



}
