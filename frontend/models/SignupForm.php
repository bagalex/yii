<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $name;
    public $sex;
    public $location;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['name', 'filter', 'filter' => 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['sex', 'filter', 'filter' => 'trim'],
            ['sex', 'required'],
            ['sex', 'string', 'min' => 2, 'max' => 255],

            ['location', 'filter', 'filter' => 'trim'],
            ['location', 'required'],
            ['location', 'string', 'min' => 2, 'max' => 255],


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
        
        $user = new User();
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->activate_code =  str_replace("-", "", Yii::$app->security->generateRandomString());
        $user->name = $this->name;
        $user->sex = $this->sex;
        $user->location = $this->location;
        $user->status = 'deleted';
        $user->registration_date = time();
        return $user->save() ? $user : null;
    }

    /**
     * Signs user up with facebook.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signupFB($attributes)
    {
        $user = new User();
        $user->email = $attributes['email'];
        $user->setPassword('');
        $user->generateAuthKey();
        $user->name = $attributes['name'];
        $user->status = 'registered';
        $user->registration_date = time();
        return $user->save() ? $user : null;
    }



}
