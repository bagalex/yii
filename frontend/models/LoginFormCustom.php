<?php
namespace frontend\models;

use Yii;

use common\models\User;
use common\models\LoginForm;

/**
 * Login form custom
 */
class LoginFormCustom extends LoginForm
{

    public $password;
    public $email;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
            [['email', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],

        ];


    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::find()->where(['email' => $this->email])->one();
        }
        if ($this->_user->status != 'registered' and $this->_user->status != 'invited') {
            Yii::$app->session->setFlash('error', 'Invalid status');

            return null;
        }


        return $this->_user;
    }

}
