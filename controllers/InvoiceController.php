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
use kartik\mpdf\Pdf;
use app\components\NumberToString;
use yii\filters\AccessControl;

use yii\db\Query;

class InvoiceController extends BaseController
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
    
    public function actionCopy($id)
    {
        $invoice = $this->findModel($id);
        $invoice->copy_count += 1;
        $invoice->save();
        return $this->redirect(['invoice/pdf','id'=>$invoice->id, 'type'=>'copy']);
    }
    
    public function actionStorno($id)
    {
        $invoice = $this->findModel($id);

        if ( isset($invoice->storno_invoice_date)) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('app','Storno invoice is already printed') );
            return $this->render('pdf-error');
        }
        
        $invoice->storno_invoice_date = date("Y-m-d");
        $invoice->storno_invoice_number = $invoice->getNextInvoiceNumber(Invoice::TYPE_STORNO);
        $invoice->save();
        return $this->redirect(['invoice/pdf','id'=>$invoice->id, 'type'=>'storno']);
    }
    
    public function getInvoiceData($newsIds,$payment_method_id,$preview = true) {
        // check whether NEW all of them
        if ( !News::isNew($newsIds) ) {
            die('Some of them are already invoiced.');
        }
        $data_set = News::getInvoiceData($newsIds);
        // preparing for storno invoice
        $storno_data_set = News::getInvoiceData($newsIds,'storno');
        
        $invoice = new Invoice();
        
        $now_date                       = new \DateTime( date( 'Y-m-d', time() ) );
        $invoice->invoice_date          = $now_date->format('Y-m-d');
        
        if ($payment_method_id == PaymentMethod::TRANSFER) {
            // ???????? Terjesztési időpont, de melyik?
            //$invoice->settle_date           = $news->distribution_date;
            // Fizetési határidő = Számla kelte + 8 nap
            $now_date->add(new \DateInterval('P8D'));
            $invoice->invoice_deadline_date = $now_date->format('Y-m-d');
        }
        elseif ($payment_method_id == PaymentMethod::CASH) {
            // Teljesítés dátuma = Számla kelte
            $invoice->settle_date           = $now_date->format('Y-m-d');
            // Fizetési határidő = Számla kelte
            $invoice->invoice_deadline_date = $now_date->format('Y-m-d');
        }
                
        $invoice->payment_method_id     = $payment_method_id;
        $invoice->client_id             = $data_set['client']->id;
        $invoice->price_summa           = $data_set['price_summa'];
        $invoice->tax_summa             = $data_set['tax_summa'];
        $invoice->all_summa             = $data_set['all_summa'];
        
        if ($preview) {
            $invoice->invoice_number    = "...";
        }
        else {
            if ($payment_method_id = PaymentMethod::TRANSFER) {
                $invoice->invoice_number    = $invoice->getNextInvoiceNumber(Invoice::TYPE_TRANSFER);
            }
            elseif ($payment_method_id = PaymentMethod::CASH) {
                $invoice->invoice_number    = $invoice->getNextInvoiceNumber(Invoice::TYPE_CASH);
            }
        }
        
        if ($payment_method_id = PaymentMethod::CASH) {
            // round to 5 Ft CASH
            $data_set['all_summa']          = round($data_set['all_summa']/5, 0) * 5;
        }
        $invoice->invoice_data          = serialize($data_set);
        $invoice->storno_invoice_data   = serialize($storno_data_set);
        
        return $invoice;
    }
    
    public function actionExecute() {
        if ( isset($_REQUEST['selection']) && $_REQUEST['selection'] && $_REQUEST['payment_method_id'] ) {
            $newsIds = $_REQUEST['selection'];
            $preview = false;
            if ( isset($_REQUEST['preview']) && $_REQUEST['preview'] ) {
                $preview = true;
            }
            $invoice = $this->getInvoiceData($newsIds, $_REQUEST['payment_method_id'], $preview);
            
            $data_set       = unserialize($invoice->invoice_data);
            
            $copy           = 1;
            $copy_count     = "";
            $invoice_type   = 'normal';
            
            $invoice_data = [
                'copy'              => $copy,
                'copy_count'        => $copy_count,
                'invoice'           => $invoice,
                'client'            => $data_set['client'],
                'items'             => $data_set['items'],
                'price_summa'       => $data_set['price_summa'],
                'tax_summa'         => $data_set['tax_summa'],
                'all_summa'         => $data_set['all_summa'],
                'all_summa_string'  => $data_set['all_summa_string'],
                'type'              => $invoice_type,
            ];
            
            if ($preview) {
                return $this->render('pdf',['data'=>$invoice_data]);
            }
            else {
                $invoice->save();
                
                foreach($newsIds as $news_id) {
                    $invoiceItem = new InvoiceItem;
                    $invoiceItem->invoice_id    = $invoice->id;
                    $invoiceItem->item_id       = $news_id;
                    $invoiceItem->item_class    = 'News';
                    $invoiceItem->save();
                    
                    $news = News::findOne($news_id);
                    $news->invoice_date = $invoice->invoice_date;
                    $news->settle_date  = $invoice->settle_date;
                    $news->status_id    = News::STATUS_INVOICED;
                    $news->save(); 
                }
                echo json_encode(['success' => true, 'invoice_id' => $invoice->id]);
            }
        }
    }
    

    
    public function actionTransfer()
    {
        /**
        * Step 1 / all news in period grouped by Client
        */
        $today = date('d', time());
        $period_to = date("Y-m-d");
        $period_from = date( "Y-m-d", strtotime('last Monday') );
        if ( isset($_REQUEST['period_to']) || isset($_REQUEST['period_from'])) {
            if ( isset($_REQUEST['period_to']) ) {
                $period_to = $_REQUEST['period_to'];
            }
            else {
                $period_to = "";
            }
            if ( isset($_REQUEST['period_from']) ) {
                $period_from = $_REQUEST['period_from'];
            }
            else {
                $period_from = "";
            }
        }
        
        $q = Client::find()
                    ->joinWith('news')
                    ->andWhere('news.payment_method_id='.PaymentMethod::TRANSFER)
                    ->andWhere('news.status_id='.News::STATUS_NEW);
        if ($period_from != "") {
            $q->andWhere("distribution_date >='".$period_from."'");
        }
        if ($period_to != "") {
            $q->andWhere("distribution_date <='".$period_to."'");
        }
        $clients = $q->all();
        
        return $this->render('transfer', [
            'period_from'   => $period_from,
            'period_to'     => $period_to,
            'clients'       => $clients,
        ]);
    }
    
    public function actionCash()
    {
        $searchModel = new NewsSearch();
        $today = date('d', time());
        $period_to = date("Y-m-d");
        if ( $today <= 3 ) {
            $searchModel->distribution_date_from = date( "Y-m-d", strtotime('first day of last month') );
            $period_from = date( "Y-m-d", strtotime('first day of last month') );
        }
        else {
            $searchModel->distribution_date_from = date( "Y-m-d", strtotime('first day of this month') );
            $period_from = date( "Y-m-d", strtotime('first day of this month') );
        }
        $searchModel->payment_method_id = PaymentMethod::CASH;
        $searchModel->status_id         = News::STATUS_NEW;
        
        /**
        * Step 2 / client's news only
        */
        if ( isset($_REQUEST['news_id']) && $_REQUEST['news_id'] ) {
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
            Yii::$app->getSession()->setFlash('danger', Yii::t('app','Invalid invoice id') );
            return $this->render('pdf-error');
        }
        
        $copy = 1;
        if (isset($_REQUEST['copy']) && $_REQUEST['copy'] && in_array($_REQUEST['copy'], ["1","2"])) {
            $copy = $_REQUEST['copy'];
        }
        
        $data_set = unserialize($invoice->invoice_data);
        $copy_count = "";

        if ( isset($_REQUEST['type']) && $_REQUEST['type'] == 'storno' ) {
            $invoice_type = 'storno';
            $data_set = unserialize($invoice->storno_invoice_data);
        }
        elseif ( isset($_REQUEST['type']) && $_REQUEST['type'] == 'copy' ) {
            $invoice_type = 'copy';
            $copy_count = $invoice->copy_count;
        }
        else {
            $invoice_type = 'normal';
        }
                
        $this->layout = 'invoice-pdf';
        
        $invoice_data = [
            'copy'              => $copy,
            'copy_count'        => $copy_count,
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
    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
        
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
