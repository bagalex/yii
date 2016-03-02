<?php

namespace frontend\controllers;

use Yii;
use frontend\models\InviteForm;
use app\models\InviteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\libreries\Maildrill;
use frontend\models\SetPasswordForm;

/**
 * InviteController implements the CRUD actions for InviteForm model.
 */
class InviteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', 'create'],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all InviteForm models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InviteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InviteForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Finds the InviteForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InviteForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InviteForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Creates a new invitation.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InviteForm();

        if ($model->load(Yii::$app->request->post()) and $user = $model->add()) {
            $maildrill = new Maildrill();
            $maildrill->setTo($model->email);
            $maildrill->setSubject('Invitation');
            $maildrill->setTemplate($this->renderPartial('invitation', [
                'link' => $user->activate_code
            ]));
            $maildrill->send();

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Confirm User
     * If confirm code is exist, password form will be return.
     * @return mixed
     */
    public function actionConfirm($confirm_code)
    {
        $user = \common\models\User::find()->where(['activate_code' => $confirm_code])->andWhere([
            'not',
            ['invite_by_user' => 0]
        ])->one();

        if (!empty($user)) {
            $model = new setPasswordForm();
            if ($model->load(Yii::$app->request->post()) and $user = $model->setPassword()) {
                $user->activate_code = '';
                $user->save();
                Yii::$app->getUser()->login($user);
            } else {
                return $this->render('setPassword', [
                    'model' => $model,
                    'email' => $user->email
                ]);
            }
        }

        return $this->goHome();
    }
}
