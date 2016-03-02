<?php

namespace backend\controllers;

use Yii;
use backend\models\Invite;
use backend\models\InviteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * InviteController implements the CRUD actions for Invite model.
 */
class InviteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index', 'view', 'delete', 'block'],
                'rules' => [
                    [
                        'actions'       => ['index', 'view', 'delete', 'block'],
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
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Invite models.
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
     * Displays a single Invite model.
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
     * Change User status to deleted.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($user = \common\models\User::find()->where(['id' => $id])->one()) {
            $user->status = 'deleted';
            $user->save();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Invite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Invite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invite::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Change User status to blocked.
     * If block is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBlock($id)
    {
        if ($user = \common\models\User::find()->where(['id' => $id])->one()) {
            $user->status = 'blocked';
            $user->save();
        }

        return $this->redirect(['index']);
    }
}
