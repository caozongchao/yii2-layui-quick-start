<?php

namespace rbac\models\form;

use Yii;
use rbac\models\User;
use yii\base\Model;

/**
 * Signup form
 */
class Signup extends Model
{
    public $username;
    public $email;
    public $mobile;
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'rbac\models\User', 'message' => 'This username has already been taken.'],
            [['username',], 'string', 'min' => 2, 'max' => 255],
            ['email', 'filter', 'filter' => 'trim'],
            // ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'rbac\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [['mobile'],'string','length'=>11]

        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;
            if ($user->save()) {
                return $user;
            }
        }
        return null;
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'email' => '电子邮箱',
            'mobile' => '手机号',
			'password' => '用户密码',
        ];
    }
}