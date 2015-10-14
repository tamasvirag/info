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
use kartik\mpdf\Pdf;

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
        $alltogether = [
            'summa' => 0,
            'change' => ['20000'=>0,'10000'=>0,'5000'=>0,'2000'=>0,'1000'=>0,'500'=>0,'200'=>0,'100'=>0,'50'=>0,'20'=>0,'10'=>0,'5'=>0],
        ];

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
                $change = $changer->change( $data['summa'] );
                
                $dealerdata = [
                    'dataProvider'  => $dataProvider,
                    'change'        => $change,
                    'summa'         => $data['summa'],
                    'dealer'        => $dealer,
                ];
                
                if ( count( $change ) ) {
                    foreach( $change as $note => $count ) {
                        $alltogether['change'][$note]   += $count;
                        $alltogether['summa']           += $note*$count;
                    }
                }
                
                $dataset[] = $dealerdata;
            }

        }
        
        $format = 'html';
        if ( isset($_REQUEST['dealer-pay-format']) ) {
            $format = $_REQUEST['dealer-pay-format'];
        }
        
        if ( $format == 'pdf' ) {
            $this->layout = 'invoice-pdf';
            
            $content = $this->render('pay', [
                'dataset'       => $dataset,
                'alltogether'   => $alltogether,
                'format'        => 'pdf',
            ]);
        
            $pdf = new Pdf([
                'mode'          => Pdf::MODE_UTF8,
                'format'        => Pdf::FORMAT_A4,
                'orientation'   => Pdf::ORIENT_PORTRAIT,
                'destination'   => Pdf::DEST_BROWSER,
                'content'       => $content,
                'cssFile'       => '',
                'cssInline'     => '',
                'options'       => ['title' => ''],
                'methods'       => []
            ]);
    
            Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
            $headers = Yii::$app->response->headers;
            $headers->add('Content-Type', 'application/pdf');
//            $headers->add('Content-Disposition: attachment; filename=terjesztok.pdf');
            
            return $pdf->render();
        }
        else {
            return $this->render('pay', [
                'dataset'       => $dataset,
                'alltogether'   => $alltogether,
                'format'        => 'html',
            ]);
        }
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
            Yii::$app->session->setFlash('success', Yii::t('app','success_create'));
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
            Yii::$app->session->setFlash('success', Yii::t('app','success_save'));
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
        Yii::$app->session->setFlash('success', Yii::t('app','success_delete'));
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
