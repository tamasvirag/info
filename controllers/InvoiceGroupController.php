<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\Invoice;
use app\models\InvoiceSearch;
use app\models\InvoiceItem;
use app\models\InvoiceGroup;
use app\models\InvoiceGroupItem;
use app\models\News;
use app\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use app\models\District;
use app\models\DistrictSearch;
use app\models\NewsDistrict;
use app\models\PaymentMethod;
use kartik\mpdf\Pdf;
use app\components\NumberToString;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use yii\db\Query;

class InvoicegroupController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['invoiceManager'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {        
        $searchModel = new InvoiceGroup();
//        var_dump(Yii::$app->request->bodyParams);
//        die();
        $dataProvider = $searchModel->search(Yii::$app->request->bodyParams);

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

    protected function findModel($id)
    {
        if (($model = InvoiceGroup::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
