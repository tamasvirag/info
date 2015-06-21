<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\Invoice;
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
use kartik\mpdf\Pdf;
use app\components\NumberToString;

use yii\db\Query;

class InvoiceController extends BaseController
{
    public function actionCash()
    {
        $searchModel = new NewsSearch();
        $today = date('d', time());
        $period_to = date("Y-m-d");
        if ( $today <= 3 ) {
            $searchModel->created_from = strtotime('first day of last month');
            $period_from = date( "Y-m-d", strtotime('first day of last month') );
        }
        else {
            $searchModel->created_from = strtotime('first day of this month');
            $period_from = date( "Y-m-d", strtotime('first day of this month') );
        }
        $searchModel->payment_method_id = PaymentMethod::CASH;
        $searchModel->status_id         = News::STATUS_NEW;
        
        /**
        * Step 3 / invoicing selected news, create invoice -> redirect to actionPdf
        */
        if ( isset($_REQUEST['selection']) && $_REQUEST['selection'] ) {
            $newsIds = $_REQUEST['selection'];
            
            // check whether NEW all of them
            if ( !News::isNew($newsIds) ) {
                die('Some of them are already invoiced.');
            }
            $data_set = News::getInvoiceData($newsIds);
            
            /**
            * Create CASH Invoice
            */
            $invoice = new Invoice();
            
            // Számla kelte = NOW
            $now_date                       = new \DateTime( date( 'Y-m-d', time() ) );
            $invoice->invoice_date          = $now_date->format('Y-m-d');
            // Teljesítés dátuma = Számla kelte
            $invoice->settle_date           = $now_date->format('Y-m-d');
            // Fizetési határidő = Számla kelte
            $invoice->invoice_deadline_date = $now_date->format('Y-m-d');
            
            $invoice->payment_method_id     = PaymentMethod::CASH;
            $invoice->setNextInvoiceNumber(Invoice::TYPE_CASH);

            // round to 5 Ft CASH
            $data_set['all_summa']          = round($data_set['all_summa']/5, 0) * 5;
            $invoice->invoice_data          = serialize($data_set);
                      
            $invoice->save();
            
            foreach($newsIds as $news_id) {
                $invoiceItem = new InvoiceItem;
                $invoiceItem->invoice_id    = $invoice->id;
                $invoiceItem->item_id       = $news_id;
                $invoiceItem->item_table    = 'news';
                $invoiceItem->save();
                
                $news = News::findOne($news_id);
                $news->invoice_date = $invoice->invoice_date;
                $news->settle_date  = $invoice->settle_date;
                $news->status_id    = News::STATUS_INVOICED;
                $news->save(); 
            }
            
            return $this->redirect(['invoice/pdf','id'=>$invoice->id]);
        }
        /**
        * Step 2 / client's news only
        */
        elseif ( isset($_REQUEST['news_id']) && $_REQUEST['news_id'] ) {
            if (($newsModel = News::findOne($_REQUEST['news_id'])) === null) {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
            $client = $newsModel->client;
            $searchModel->client_id = $client->id;
            
            $dataProvider = $searchModel->search();
            return $this->render('cash2', [
                'searchModel'   => $searchModel,
                'dataProvider'  => $dataProvider,
                'news_id'       => $newsModel->id,
                'period_from'   => $period_from,
                'period_to'     => $period_to,
                'client'        => $client,
            ]);
        }
        /**
        * Step 1 / all news
        */
        else {
            $dataProvider = $searchModel->search();
            return $this->render('cash', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'period_from'   => $period_from,
                'period_to'     => $period_to,
            ]);
        }        
    }

    public function actionPdf($id)
    {
        if (($invoice = Invoice::findOne($id)) === null) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('app','Invalid invoice id.') );
            return $this->render('pdf-error');
        }
        if ( $invoice->printed ) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('app','Invoice is already printed.') );
            return $this->render('pdf-error');
        }
        
        $data_set = unserialize($invoice->invoice_data);
        
        // egyelőre nincs használatban /////////////
        if ( isset($_REQUEST['type']) && $_REQUEST['type'] == 'storno' ) {
            $invoice_type = 'storno';
        }
        elseif ( isset($_REQUEST['type']) && $_REQUEST['type'] == 'copy' ) {
            $invoice_type = 'copy';
        }
        else {
            $invoice_type = 'normal';
        }
        ////////////////////////////////////////////
        
        
        $this->layout = 'invoice-pdf'; 
        $copy = 1;

        $invoice_data = [
            'copy'              => $copy,
            'invoice'           => $invoice,
            'client'            => $data_set['client'],
            'items'             => $data_set['items'],
            'price_summa'       => $data_set['price_summa'],
            'tax_summa'         => $data_set['tax_summa'],
            'all_summa'         => $data_set['all_summa'],
            'all_summa_string'  => $data_set['all_summa_string'],
            'type'              => $invoice_type,
        ];
        
        $content = $this->render('pdf',['data'=>$invoice_data]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '',
            'cssInline' => '',
            'options' => ['title' => ''],
            'methods' => [
                'SetHeader' => ['<p class="small">{nb} / {PAGENO}. oldal</p>'],
                'SetFooter' => ['<p align="left" class="small">Terjesztés - A számlaprogram megfelel a PM 34/1999 (XII.26) rendeletnek</p>'],
            ]
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        
        $invoice->printed = 1;
        $invoice->save();
        
        return $pdf->render();
    }
    
    public function actionPaymentdemand($id)
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
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
