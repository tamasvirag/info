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
use app\components\Banknote;

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
        $dataset = [];

        if ( isset( Yii::$app->request->bodyParams['dealers'] ) ) {
        
            foreach( Yii::$app->request->bodyParams['dealers'] as $dealer_id ) {
                
                $dealerdata = [];
            
                $dealer = Dealer::findOne($dealer_id);
                $data = $dealer->getPaymentByDistributionDate(
                    isset( Yii::$app->request->bodyParams['distribution_date_from'] )?Yii::$app->request->bodyParams['distribution_date_from']:null,
                    isset( Yii::$app->request->bodyParams['distribution_date_to'] )?Yii::$app->request->bodyParams['distribution_date_to']:null);
                $dataProvider = new ArrayDataProvider([
                    'allModels' => $data['rows'],
                    'sort' => [
                        'attributes' => ['news_name', 'district_name'],
                    ],
                    'pagination' => [
                        'pageSize' => 10000,
                    ],
                ]);
                
                $changer = new Banknote();
                $change = $changer->change($data['summa']);
                
                $dealerdata = [
                    'dataProvider'  => $dataProvider,
                    'change'        => $change,
                    'summa'         => $data['summa'],
                    'dealer'        => $dealer,
                ];
                
                $dataset[] = $dealerdata;
            }

        }

        return $this->render('pay', [
            'dataset'  => $dataset,
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
