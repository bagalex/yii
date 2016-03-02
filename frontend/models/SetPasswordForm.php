<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SetPasswordForm extends Model
{

    public $email;
    public $password;


    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['email', 'email'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function setPassword()
    {

        if (!$this->validate()) {
            return null;
        }

        $this->_user = User::find()->where(['email' => $this->email])->one();

        if (!empty($this->_user)) {
            $this->_user->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            $this->_user->registration_date = time();
            $this->_user->save();
        }

        return $this->_user;
    }


}
