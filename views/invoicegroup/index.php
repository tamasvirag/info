<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Client;
use app\models\User;
use app\models\Invoice;
use app\models\InvoiceSearch;
use app\models\PaymentMethod;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\StringHelper;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Invoicing history');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin([
                                        'options'=>['id'=>'search-invoice'],
                                        'method'=>'get',
                                    ] ); ?>
    <div class="well">
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($searchModel, 'created_by')->widget( Select2::classname(), [
                    'data'           => ArrayHelper::map( User::find()->all(), 'id', 'full_name' ),
                    'language'       => 'hu',
                    'options'        => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions'  => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-3"><?= $form->field($searchModel, 'created_from')->widget(
                                                DateRangePicker::classname(), [
                                                        'model'         => $searchModel,
                                                        'name'          => 'created_from',
                                                        'value'         => $searchModel->created_from,
                                                        'nameTo'        => StringHelper::basename($searchModel::className()).'[created_to]',
                                                        'attributeTo'   => 'created_to',
                                                        'valueTo'       => $searchModel->created_to,
                                                        'language'      => 'hu',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd'
                                                        ]
                                                    ]
                                                    ); ?>    
            </div>
            <div class="col-md-2">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary mt-21']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
        'layout'=>'{summary}{pager}{items}{pager}',
        'columns' => [
            [
                'attribute' => 'id',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( $model->id ),['view', 'id'=>$model->id] );
                },
                'options' => ['width'=>'2%'],
            ],
            [
                'attribute' => 'created_at',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return date("Y-m-d H:i:s", $model->created_at);
                },
                'options' => ['width'=>'15%'],
            ],
            [
                'attribute' => 'created_by',
                'format'    => 'raw',
                'value' => 'createdByLabel',
                'options' => ['width'=>'15%'],
            ],
            [
                'attribute' => 'invoices',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return $model->invoicesLinks;
                }
            ],
            [
                'attribute' => '',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( \Yii::t('app','Print Envelopes'), Url::to(['invoiceenvelope/index','invoice_group_id'=>$model->id]), ['target'=>'_blank']  );
                },
                'options' => ['width'=>'15%'],
            ],
//            ['class' => 'yii\grid\ActionColumn','template'=>'{view}'],
        ],
    ]); ?>

</div>
