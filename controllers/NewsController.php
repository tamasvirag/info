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

use yii\db\Query;

class NewsController extends BaseController
{
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionIndexinvoice()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('invoice-index', [
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
        $model = new News();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->status_id = News::STATUS_NEW;
            $model->save();
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
                    $newsDistrict = new NewsDistrict();
                    $newsDistrict->news_id          = $model->id;
                    $newsDistrict->district_id      = $district_id;
                    $newsDistrict->amount           = $newsDistrictPost['amount'][$district_id];
                    $newsDistrict->block            = $newsDistrictPost['block'][$district_id];
                    $newsDistrict->block_price      = str_ireplace(",", ".", $newsDistrictPost['block_price'][$district_id] );
                    $newsDistrict->block_price_real = str_ireplace(",", ".", $newsDistrictPost['block_price_real'][$district_id] );
                    $newsDistrict->house            = $newsDistrictPost['house'][$district_id];
                    $newsDistrict->house_price      = str_ireplace(",", ".", $newsDistrictPost['house_price'][$district_id] );
                    $newsDistrict->house_price_real = str_ireplace(",", ".", $newsDistrictPost['house_price_real'][$district_id] );
                    $newsDistrict->save();
                }
                
            }
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app','success_save'));
            return $this->redirect(['update','id'=>$model->id]);
        } else {
            $searchModel = new DistrictSearch();
            $searchModel->news_id = $id;
            $dataProvider = $searchModel->search();
            return $this->render('update', [
                'model' => $model,
                'news_id' => $id,
                'dataProvider' => $dataProvider,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
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
