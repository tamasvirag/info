<?php

namespace app\controllers;

use Yii;
use app\models\ClientCompany;
use app\models\ClientCompanySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ClientCompanyController implements the CRUD actions for ClientCompany model.
 */
class ClientcompanyController extends Controller
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

    /**
     * Lists all ClientCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientCompanySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClientCompany model.
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
     * Creates a new ClientCompany model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClientCompany();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['client/update', 'id' => $model->client_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ClientCompany model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $companymodel = $this->findModel($id);

        if ($companymodel->load(Yii::$app->request->post()) && $companymodel->save()) {
            return $this->redirect(['client/update', 'id' => $companymodel->client_id]);
        } else {
            return $this->render('update', [
                'companymodel' => $companymodel,
            ]);
        }
    }

    /**
     * Deletes an existing ClientCompany model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $client_id = $this->findModel($id)->client_id;
        $this->findModel($id)->delete();
        return $this->redirect(['client/update', 'id' => $client_id]);
    }

    /**
     * Finds the ClientCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClientCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClientCompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
