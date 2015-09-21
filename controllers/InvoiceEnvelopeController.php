<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\Invoice;
use app\models\News;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\components\BaseController;
use app\models\PaymentMethod;
use app\models\InvoiceGroup;
use app\models\InvoiceGroupItem;
use kartik\mpdf\Pdf;
use app\components\NumberToString;
use yii\filters\AccessControl;

use yii\db\Query;

class InvoiceenvelopeController extends BaseController
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
        $this->layout = 'invoice-pdf';
        
        $invoiceIds = [];
        if (isset($_REQUEST['id']) && $_REQUEST['id']) {
            $invoiceIds[] = $_REQUEST['id'];
        }
        elseif (isset($_REQUEST['invoice_group_id']) && $_REQUEST['invoice_group_id']) {
            if (($invoiceGroup = InvoiceGroup::findOne($_REQUEST['invoice_group_id'])) !== null) {
                $invoiceIds = $invoiceGroup->invoiceIds;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        if (!count($invoiceIds)) {
            Yii::$app->getSession()->setFlash('danger', Yii::t('app','Invalid invoice id') );
            return $this->render('pdf-error');
        }
        
        $dataArray = [];
        foreach( $invoiceIds as $invoice_id ) {
            $invoice    = Invoice::findOne($invoice_id);
            $dataSet    = unserialize($invoice->invoice_data);          
            $dataArray[$dataSet['client']->id] = $dataSet['client'];            
        }
        
        
        $content = $this->render('pdf-envelope',['dataArray'=>$dataArray]);
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [162,229], //162x229 LC5  //Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '',
            'cssInline' => '',
            'options' => ['title' => ''],
            'methods' => []
        ]);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/pdf');
        
        return $pdf->render();
    }
    
}
