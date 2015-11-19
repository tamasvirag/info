<?php

namespace app\controllers;

use Yii;
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

use yii\db\Query;

class NewsController extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['newsManager'],
                    ],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $dataProvider2      = $searchModel->search(Yii::$app->request->queryParams, 999999); // pagesize
        $net_revenue_total  = 0;
        $newscount_total    = 0;
        $news               = $dataProvider2->getModels();
        if ( count( $news ) ) {
            foreach($news as $onenews) {
                $net_revenue_total  += $onenews->net_revenue;
                $newscount_total    += $onenews->newscount;
            }
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'net_revenue_total' => $net_revenue_total,
            'newscount_total'   => $newscount_total,
        ]);
    }
    
    public function actionIndexinvoice()
    {
        $searchModel    = new NewsSearch();
        $dataProvider   = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('invoice-index', [
            'searchModel'   => $searchModel,
            'dataProvider'  => $dataProvider,
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
        $model = new News();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->status_id = News::STATUS_NEW;
            $model->save();
            $model->updateNewscountRevenue();
            Yii::$app->session->setFlash('success', Yii::t('app','success_create'));
            return $this->redirect(['update','id'=>$model->id]);
        } else {
            $searchModel = new DistrictSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('create', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    
    public function actionCreatefrom($id)
    {
        $model                  = $this->findModel($id);
        $newsDistricts          = $model->newsDistricts;
        
        $newModel               = new News();
        $newModel->attributes   = $model->attributes;
        $newModel->id           = null;
        $newModel->status_id    = News::STATUS_NEW;
        $newModel->invoice_date = null;
        $newModel->settle_date  = null;
        $newModel->save();
        
        if (count($newsDistricts)) {
            foreach($newsDistricts as $nD) {
                $newsDistrictModel              = new NewsDistrict();
                $newsDistrictModel->attributes  = $nD->attributes;
                $newsDistrictModel->news_id     = $newModel->id;
                $newsDistrictModel->save();
            }
        }
        Yii::$app->session->setFlash('success', Yii::t('app','success_copy'));
        return $this->redirect(['update','id' => $newModel->id]);
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ( count( Yii::$app->request->post('News') ) ) {
        
            $model->deleteDistricts();
            if ( count( Yii::$app->request->post('selection') ) ) {
                $newsDistrictPost = Yii::$app->request->post('newsDistrict');
                foreach( Yii::$app->request->post('selection') as $district_id ) {
                    $district                       = District::findOne($district_id);
                    $newsDistrict                   = new NewsDistrict();
                    $newsDistrict->news_id          = $model->id;
                    $newsDistrict->district_id      = $district_id;
                    $newsDistrict->block            = (isset($newsDistrictPost['block'][$district_id])&&$newsDistrictPost['block'][$district_id]!="")?$newsDistrictPost['block'][$district_id]:$district->block;
                    $newsDistrict->block_price      = (isset($newsDistrictPost['block_price'][$district_id])&&$newsDistrictPost['block_price'][$district_id]!="")?str_ireplace(",", ".", $newsDistrictPost['block_price'][$district_id] ):$district->block_price;
                    $newsDistrict->block_price_real = (isset($newsDistrictPost['block_price_real'][$district_id])&&$newsDistrictPost['block_price_real'][$district_id]!="")?str_ireplace(",", ".", $newsDistrictPost['block_price_real'][$district_id] ):$district->block_price_real;
                    $newsDistrict->house            = (isset($newsDistrictPost['house'][$district_id])&&$newsDistrictPost['house'][$district_id]!="")?$newsDistrictPost['house'][$district_id]:$district->house;
                    $newsDistrict->house_price      = (isset($newsDistrictPost['house_price'][$district_id])&&$newsDistrictPost['house_price'][$district_id]!="")?str_ireplace(",", ".", $newsDistrictPost['house_price'][$district_id] ):$district->house_price;
                    $newsDistrict->house_price_real = (isset($newsDistrictPost['house_price_real'][$district_id])&&$newsDistrictPost['house_price_real'][$district_id]!="")?str_ireplace(",", ".", $newsDistrictPost['house_price_real'][$district_id] ):$district->house_price_real;
//                    $newsDistrict->amount = $newsDistrict->house + $newsDistrict->block;
                    $newsDistrict->save();
                }
            }
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->updateNewscountRevenue();
            Yii::$app->session->setFlash('success', Yii::t('app','success_save'));
            return $this->redirect(['update','id'=>$model->id]);
        } else {
            $searchModel = new DistrictSearch();
            $searchModel->news_id = $id;
            $dataProvider = $searchModel->search();
            return $this->render('update', [
                'model'         => $model,
                'news_id'       => $id,
                'dataProvider'  => $dataProvider,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app','success_delete'));
        return $this->redirect(['index']);
    }
    
    public function actionInvoicepdf($id)
    {
        if ( isset($_REQUEST['type']) && $_REQUEST['type'] == 'storno' ) {
            $invoice_type = 'storno';
        }
        elseif ( isset($_REQUEST['type']) && $_REQUEST['type'] == 'copy' ) {
            $invoice_type = 'copy';
        }
        else {
            $invoice_type = 'normal';
        }
        
        $this->layout = 'invoice-pdf';
        $news = $this->findModel($id);
        
        $news_invoice_data  = $news->setInvoiceData($invoice_type);
        $items              = $news_invoice_data['items'];
        $price_summa        = $news_invoice_data['price_summa'];
        $tax_summa          = $news_invoice_data['tax_summa'];
        $all_summa          = $news_invoice_data['all_summa'];
        $all_summa_string   = $news_invoice_data['all_summa_string'];
        
        $copy = 1;

        $invoice_data = [
            'copy'              => $copy,
            'news'              => $news,
            'client'            => $news->client,
            'items'             => $items,
            'price_summa'       => $price_summa,
            'tax_summa'         => $tax_summa,
            'all_summa'         => $all_summa,
            'all_summa_string'  => $all_summa_string,
            'type'              => $invoice_type,
        ];
        
        $content = $this->render('invoice-pdf',['data'=>$invoice_data]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '',
            'cssInline' => '',
            'options' => ['title' => $news->name],
            'methods' => [
                'SetHeader' => ['<p class="small">{nb} / {PAGENO}. oldal</p>'],
                'SetFooter' => ['<p align="left" class="small">Terjesztés - A számlaprogram megfelel a PM 34/1999 (XII.26) rendeletnek</p>'],
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        
        return $pdf->render();
    }
    
    public function actionInvoicepaymentdemand($id)
    {   
        $this->layout = 'invoice-payment-demand';
        $news = $this->findModel($id);
        $items = [];
        
        
        $copy = 1;

        $invoice_data = [
            'copy'              => $copy,
            'news'              => $news,
            'client'            => $news->client,
            'items'             => $items,
            'price_summa'       => $price_summa,
            'tax_summa'         => $tax_summa,
            'all_summa'         => $all_summa,
            'all_summa_string'  => $all_summa_string,
            'type'              => $invoice_type,
        ];
        $content = $this->render('invoice-pdf',['data'=>$invoice_data]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '',
            'cssInline' => '',
            'options' => ['title' => $news->name],
            'methods' => [
                'SetHeader' => ['<p class="small">{nb} / {PAGENO}. oldal</p>'],
                'SetFooter' => ['<p align="left" class="small">Terjesztés - A számlaprogram megfelel a PM 34/1999 (XII.26) rendeletnek</p>'],
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        
        return $pdf->render();
    }

    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
