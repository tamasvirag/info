<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\ClientCompany;
use app\models\ClientSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

/**
 * ClientController implements the CRUD actions for Client model.
 */
class ClientController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['clientManager'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
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
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                $data = [
                    'data'      => 'success',
                    'status'    => 'added',
                    'clientId'  => $model->id,
                    'clientNameWithAddress'  => $model->nameWithAddress,
                    'adsCount'  => 0,
                ];
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $data;
            }
            else {
                Yii::$app->session->setFlash('success', Yii::t('app','success_create'));
                return $this->redirect(['update','id'=>$model->id]);
            }
        } elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model
            ]);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $companymodel = new ClientCompany();
        $companiesDataProvider = new ActiveDataProvider([
            'query' => ClientCompany::find()->where('client_id = '.$model->id),
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->isAjax) {
                $data = [
                    'data'      => 'success',
                    'status'    => 'updated',
                    'clientId'  => $model->id,
                    'clientNameWithAddress'  => $model->nameWithAddress,
                    'adsCount'  => count($model->ads),
                ];
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $data;
            }
            else {
                Yii::$app->session->setFlash('success', Yii::t('app','success_save'));
                return $this->redirect(['update','id'=>$model->id]);
            }
        } elseif (Yii::$app->request->isAjax) {
            return $this->renderAjax('_form', [
                'model' => $model,
                'companymodel' => $companymodel,
                'companiesDataProvider' => $companiesDataProvider,
            ]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'companymodel' => $companymodel,
                'companiesDataProvider' => $companiesDataProvider,
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app','success_delete'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionGetclientjsonbyid($id) {
        $model = $this->findModel($id);
        $ret = ['payment_method_id'=>$model->payment_method_id];
        echo json_encode($ret);
    }
}
