<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\Invoice;
use app\models\InvoiceSearch;
use app\models\InvoiceItem;
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
use app\models\InvoiceGroup;
use app\models\InvoiceGroupItem;
use kartik\mpdf\Pdf;
use app\components\NumberToString;
use yii\filters\AccessControl;

use yii\db\Query;

class InvoicedemandController extends BaseController
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
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}

?>