<?php
namespace frontend\models;

use Yii;
use common\models\User;
/**
 * Signup form
 */
class InviteForm extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

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
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address already exist.'],
        ];
    }

    /**
     * Add user.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function add()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->generateAuthKey();
        $user->activate_code =  str_replace("-", "", Yii::$app->security->generateRandomString());
        $user->status = 'invited';
        $user->invite_by_user = Yii::$app->user->identity->getId();
        $user->sent_date = time();
        return $user->save() ? $user : null;
    }

}
