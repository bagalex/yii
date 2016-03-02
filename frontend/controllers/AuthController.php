<?php
namespace frontend\controllers;

use Yii;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\models\LoginFormCustom;

use frontend\libreries\Maildrill;

/**
 * Site controller
 */
class AuthController extends Controller
{

    public $successUrl = 'Success';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow'   => true,
                        'roles'   => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth'    => [
                'class'           => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'oAuthSuccess'],
            ],
        ];
    }


    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginFormCustom();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {

            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);

    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $maildrill = new Maildrill();
                $maildrill->setTo($model->email);
                $maildrill->setSubject('Invitation');
                $maildrill->setTemplate($this->renderPartial('message', [
                    'link' => $user->activate_code
                ]));
                $maildrill->send();
                Yii::$app->session->setFlash('success', 'Check your email for confirm registration.');
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Confirm User
     * If confirm code is exist, login User.
     * @return mixed
     */
    public function actionConfirm($confirm_code)
    {
        $user = \common\models\User::find()->where(['activate_code' => $confirm_code, 'invite_by_user' => 0])->one();

        if (!empty($user)) {
            $user->activate_code = '';
            $user->status = 'registered';
            $user->save();
            Yii::$app->getUser()->login($user);
        }

        return $this->goHome();
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }


    /**
     * This function will be triggered when user is successfuly authenticated using facebook.
     *
     * @param yii\authclient\ClientInterface $client
     * @return boolean|yii\web\Response
     */
    public function oAuthSuccess($client)
    {
        $attributes = $client->getUserAttributes();

        $user = \common\models\User::find()->where(['email' => $attributes['email']])->one();

        if (empty($user)) {
            $model = new SignupForm();
            $user = $model->signupFB($attributes);
        } else {
            $user->name = $attributes['name'];
            $user->update();
        }

        if (!empty($user)) {
            Yii::$app->user->login($user);
        }

        return $this->goHome();
    }
}

