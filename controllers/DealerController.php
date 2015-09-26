<?php

namespace app\controllers;

use Yii;
use app\models\Dealer;
use app\models\DealerSearch;
use app\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;

class DealerController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['dealerManager'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionPay()
    {
        $searchModel = new NewsSearch();
        $searchModel->search(Yii::$app->request->queryParams);
        $data = ['summa'=>0];
        
        $dataProvider = new ArrayDataProvider();
        
        if ( isset( $searchModel->dealer_id ) && $searchModel->dealer_id ) {
            $dealer = new Dealer();
            $dealer->id = $searchModel->dealer_id;
            $data = $dealer->getPaymentByDistributionDate( $searchModel->distribution_date_from, $searchModel->distribution_date_to);
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $data['rows'],
                'sort' => [
                    'attributes' => ['news_name', 'district_name'],
                ],
                'pagination' => [
                    'pageSize' => 10000,
                ],
            ]);
        }
        
//        var_dump($dataProvider);

        return $this->render('pay', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
            'summa'         => $data['summa'],
        ]);
    }
    
    public function actionIndex()
    {
        $searchModel = new DealerSearch();
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
        $model = new Dealer();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
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
        if (($model = Dealer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
