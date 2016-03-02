<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout', 'index'],
                'rules' => [
                    [
                        'actions'       => ['logout', 'index'],
                        'allow'         => true,
                        'roles'         => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isAdmin;
                        }
                    ],
                ]
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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }


}
