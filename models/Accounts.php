<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "accounts".
 *
 * @property int $id
 * @property string|null $inn
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $password
 * @property string $password_hash
 * @property string $auth_key
 * @property int|null $gender 0-female, 1-male
 * @property string|null $date_of_birth
 * @property string|null $date_create
 */
class Accounts extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{

    public $is_admin;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'accounts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender'], 'integer'],
            [['date_of_birth', 'date_create'], 'safe'],
            [['inn'], 'string', 'max' => 10],
            [['first_name', 'last_name', 'password_hash','auth_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'inn' => 'Inn',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'password_hash' => 'Password',
            'auth_key' => 'Auth key',
            'gender' => 'Gender',
            'date_of_birth' => 'Date Of Birth',
            'date_create' => 'Date Create',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function isPasswordResetTokenValid($token)
    {

        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['first_name' => $username]);
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates "remember me" authentication key
     */
    public function isAdmin()
    {
        $this->is_admin = Yii::$app->user->identity->access;
    }



}
