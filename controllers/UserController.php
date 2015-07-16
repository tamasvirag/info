<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use yii\filters\AccessControl;

class UserController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['userManager'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $searchModel = new User();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($model->id);
            if ( Yii::$app->request->post('roles') !== null && is_array(Yii::$app->request->post('roles')) ) {
                foreach(Yii::$app->request->post('roles') as $rolename) {
                    $role = $auth->getRole($rolename);
                    $auth->assign($role, $model->id);
                }
            }
            return $this->redirect(['index']);
        } else {
            $auth = Yii::$app->authManager;
            $userAssignments = [];
            $allRoles = $auth->getRoles();
            return $this->render('create', [
                'model'             => $model,
                'allRoles'          => $allRoles,
                'userAssignments'   => $userAssignments,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $auth = Yii::$app->authManager;
            $auth->revokeAll($model->id);
            if ( Yii::$app->request->post('roles') !== null && is_array(Yii::$app->request->post('roles')) ) {
                foreach(Yii::$app->request->post('roles') as $rolename) {
                    $role = $auth->getRole($rolename);
                    $auth->assign($role, $model->id);
                }
            }
            return $this->redirect(['index']);
        } else {
            $auth = Yii::$app->authManager;
            $userAssignments = $auth->getAssignments($model->id);
            $allRoles = $auth->getRoles();
            return $this->render('update', [
                'model'             => $model,
                'allRoles'          => $allRoles,
                'userAssignments'   => $userAssignments,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
