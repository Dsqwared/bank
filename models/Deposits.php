<?php

namespace app\models;

use Yii;
use app\models\Transactions;

/**
 * This is the model class for table "deposits".
 *
 * @property int $id
 * @property int|null $account_id
 * @property float|null $deposit
 * @property float|null $percent
 * @property string|null $date_create
 */
class Deposits extends \yii\db\ActiveRecord
{
    public $deposits;
    public $today;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'deposits';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_id'], 'integer'],
            [['deposit', 'percent'], 'number'],
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
            'deposit' => 'Deposit',
            'percent' => 'Percent',
            'date_create' => 'Date Create',
        ];
    }

    public function getAccounts()
    {
        return $this->hasOne(Accounts::className(), ['id' => 'account_id']);
    }

    /*
     *  type = 0 - data for comission
     *  type = 1 - data for deposits
     */
    private function getData($type = false){
        $subQuery = (new \yii\db\Query())
            ->select('COUNT(*)')
            ->where('DATE(t1.date_create) = DATE(:cur_date)', [':cur_date' => $this->today])
            ->andwhere('d.account_id = t1.account_id')
            ->andwhere('t1.`type` = :type',[':type' => $type])
            ->from('transactions t1');
        $dep = self::find()->alias('d')->select(['*']);
        if($type){
            $LastDayOfMonth = ($this->today == date('Y-m-t', strtotime($this->today)));
            if ($LastDayOfMonth) {
                $dep->where("d.date_create = LAST_DAY(d.date_create)");
            } else {
                $dep->where("DAY(d.date_create) = DAY(:cur_date)", [':cur_date' => $this->today]);
            }
        }
        $dep->where(['=',0,$subQuery]);
        $dep->asArray();
        return $dep->all();
    }

    public function getDepositFee($data)
    {
        if ($data['deposit'] >= 0 && $data['deposit'] < 1000) {
            $fee = 50;
        } elseif ($data['deposit'] >= 1000 && $data['deposit'] < 10000) {
            $fee = $data['deposit'] * 0.06;
        } else {
            $fee = $data['deposit'] * 0.07;
            $fee = $fee < 5000 ? $fee : 5000;
        }
        $curMonth = (int)DATE('m');
        $createMonth = (int)DATE('m',strtotime($data['date_create']));
        $createDay = (int)DATE('d',strtotime($data['date_create']));
        if ($curMonth - $createMonth  == 1) {
            $createMonthDays = date('t',strtotime($data['date_create']));
            $depositDays = $createMonthDays - $createDay + 1 ;
            $monthK = $depositDays / $createMonthDays;
            $fee *= $monthK;
        }

        $data['deposit'] -= $fee;
        return $data;
    }

    public function getDepositPercentSum($deposit)
    {
        $daysOfYear = date('L',strtotime($this->today))?366:365;
        $daysOfMonth = date('t',strtotime($this->today));
        $percentOfMonth = $daysOfMonth*floatval($deposit['percent'])/$daysOfYear;
        $deposit['deposit'] += ($deposit['deposit'] * floatval($percentOfMonth) / 100);
        return $deposit;
    }

    public function updateDeposit($deposit, $type)
    {
        $mod = $this::findOne($deposit['id']);
        $addVal = $deposit['deposit'] - $mod->deposit;
        $mod->deposit = $deposit['deposit'];
        if ($mod->save()) {
            $transactions = new Transactions();
            $deposit['type'] = $type;
            $transactions->addTransaction($deposit, $addVal,$this->today);
        }
    }

    public function doDepositActions()
    {
        $this->today = DATE('Y-m-d');
        //$this->today = DATE('2020-08-01');
        $depositsData = $this->getData(1);
        if ($depositsData) {
            foreach ($depositsData as $deposit) {
                $deposit = $this->getDepositPercentSum($deposit);
                $this->updateDeposit($deposit, 1);
            }
        } else {
            echo("Today doesn't found deposits for processing"). PHP_EOL;
        }
    }

    public function doComissionsActions()
    {
        if($this->today == date("Y-m-01",strtotime($this->today))) {
            $comissionsData = $this->getData();
            if ($comissionsData) {
                foreach ($comissionsData as $data) {
                    $data = $this->getDepositFee($data);
                    $this->updateDeposit($data,0);
                }
            }
        }else {
            echo("Today is not first day of month");
        }
    }

    public static function getReportAverage(){
        $dep = self::find()->alias('d')->select(['SUM(IF(TIMESTAMPDIFF (YEAR, a.date_of_birth, CURDATE()) >= 18 AND TIMESTAMPDIFF (YEAR, a.date_of_birth, CURDATE()) < 25 , d.deposit, 0))/COUNT(d.id) AS `group1`',
            'SUM(IF(TIMESTAMPDIFF (YEAR, a.date_of_birth, CURDATE()) >= 25 AND TIMESTAMPDIFF (YEAR, a.date_of_birth, CURDATE()) < 50 , d.deposit, 0))/COUNT(d.id) AS `group2`',
            'SUM(IF(TIMESTAMPDIFF (YEAR, a.date_of_birth, CURDATE()) >= 50 , d.deposit, 0))/COUNT(d.id) AS `group3`'])
            ->join('JOIN', 'accounts a', 'a.id = d.account_id')
            ->asArray();
        return $dep->all();
    }

}
