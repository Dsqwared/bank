<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Signup form
 */
class SignupForm extends Model
{

    public $first_name;
    public $last_name;
    public $inn;
    public $gender;
    public $date_of_birth;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['first_name', 'trim'],
            ['first_name', 'required'],
            ['first_name', 'unique', 'targetClass' => '\app\models\Accounts', 'message' => 'This first name has already been taken.'],
            ['first_name', 'string', 'min' => 2, 'max' => 255],
            ['last_name', 'trim'],
            ['last_name', 'required'],
            ['last_name', 'string', 'min' => 2, 'max' => 255],
            ['inn', 'trim'],
            ['inn', 'required'],
            ['inn', 'unique', 'targetClass' => '\app\models\Accounts', 'message' => 'This Inn has already been taken.'],
            ['inn', 'string', 'min' => 10, 'max' => 10],
            ['gender', 'string', 'max' => 1],
            [['date_of_birth'], 'required'],
            [['date_of_birth'], 'safe'],
            ['date_of_birth', 'date', 'format' => 'YYYY-MM-DD'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {

        if (!$this->validate()) {
            return null;
        }

        $Accounts = new Accounts();
        $Accounts->first_name = $this->first_name;
        $Accounts->last_name = $this->last_name;
        $Accounts->inn = $this->inn;
        $Accounts->gender = $this->gender;
        $Accounts->date_of_birth = $this->date_of_birth;
        $Accounts->setPassword($this->password);
        $Accounts->generateAuthKey();
        return $Accounts->save() ? $Accounts : null;
    }

}