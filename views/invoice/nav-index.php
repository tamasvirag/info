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
use app\models\Office;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\StringHelper;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\InvoiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Invoices');
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
            <div class="col-md-2"><?= $form->field($searchModel, 'invoice_number')->textInput(['maxlength' => 255]) ?></div>
            <div class="col-md-2"><?= $form->field($searchModel, 'payment_method_id')->dropDownList( ArrayHelper::map( PaymentMethod::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?></div>
        </div>
        <div class="row">

            <div class="col-md-3"><?= $form->field($searchModel, 'invoice_date_from')->widget(
                                                DateRangePicker::classname(), [
                                                        'model' => $searchModel,
                                                        'name' => 'invoice_date_from',
                                                        'value' => $searchModel->invoice_date_from,
                                                        'nameTo' => StringHelper::basename($searchModel::className()).'[invoice_date_to]',
                                                        'attributeTo' => 'invoice_date_to',
                                                        'valueTo' => $searchModel->invoice_date_to,
                                                        'language' => 'hu',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd'
                                                            ]
                                                    ]
                                                    ); ?>
            </div>
            <div class="col-md-3"><?= $form->field($searchModel, 'invoice_deadline_date_from')->widget(
                                                DateRangePicker::classname(), [
                                                        'model' => $searchModel,
                                                        'name' => 'invoice_deadline_date_from',
                                                        'value' => $searchModel->invoice_deadline_date_from,
                                                        'nameTo' => StringHelper::basename($searchModel::className()).'[invoice_deadline_date_to]',
                                                        'attributeTo' => 'invoice_deadline_date_to',
                                                        'valueTo' => $searchModel->invoice_deadline_date_to,
                                                        'language' => 'hu',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd'
                                                            ]
                                                    ]
                                                    ); ?>
            </div>
            <div class="col-md-3"><?= $form->field($searchModel, 'settle_date_from')->widget(
                                                DateRangePicker::classname(), [
                                                        'model' => $searchModel,
                                                        'name' => 'settle_date_from',
                                                        'value' => $searchModel->settle_date_from,
                                                        'nameTo' => StringHelper::basename($searchModel::className()).'[settle_date_to]',
                                                        'attributeTo' => 'settle_date_to',
                                                        'valueTo' => $searchModel->settle_date_to,
                                                        'language' => 'hu',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd'
                                                            ]
                                                    ]
                                                    ); ?>
            </div>
            <div class="col-md-1">
                <?= Html::hiddenInput('search-destination', 'screen', ['id' => 'search-destination']) ?>
                <?= Html::buttonInput(Yii::t('app', 'Search'), ['class' => 'btn btn-primary mt-21 btn-nav-submit']) ?>
            </div>
            <div class="col-md-2">
                <?= Html::buttonInput('Számlák export (.xml)', ['class'=>'btn btn-primary mt-21 btn-nav-export']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <div class="row">
    <div class="col-md-12">
    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
        'layout'=>'{summary}{pager}{items}{pager}',
        'columns' => [
            [
                'attribute' => 'invoice_number',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( $model->invoice_number ),['view', 'id'=>$model->id] );
                }
            ],

            [
                'attribute' => 'client_id',
                'format'    => 'raw',
                'value' => 'clientLabel',
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[client_id]',
                                'value' => $searchModel->client_id,
                                'data' => ArrayHelper::merge([''=>''], ArrayHelper::map( Client::find()->all(), 'id', 'name' )),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'15%'],
            ],

            [
                'attribute' => 'payment_method_id',
                'format'    => 'raw',
                'value' => 'paymentMethodLabel',
                'filter' => ArrayHelper::map( PaymentMethod::find()->all(), 'id', 'name' )
            ],

            [
                'attribute' => 'price_summa',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return '<nobr>'.$model->price_summa.'</nobr>';
                }
            ],
            [
                'attribute' => 'tax_summa',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return '<nobr>'.$model->tax_summa.'</nobr>';
                }
            ],
            [
                'attribute' => 'all_summa',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return '<nobr>'.$model->all_summa.'</nobr>';
                }
            ],

            [
                'label' => \Yii::t('app','invoice_date_abb'),
                'attribute'=>'invoice_date',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return '<nobr>'.$model->invoice_date.'</nobr>';
                }
            ],
            [
                'label' => \Yii::t('app','completion_date_abb'),
                'attribute'=>'completion_date',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return '<nobr>'.$model->completion_date.'</nobr>';
                }
            ],
            [
                'label' => \Yii::t('app','invoice_deadline_date_abb'),
                'attribute'=>'invoice_deadline_date',
            ],
            [
                'label' => \Yii::t('app','settle_date_abb'),
                'attribute'=>'settle_date',
                'format'    => 'raw',
                'value' => function( $model ) {
                    if ( isset($model->settle_date) ) {
                        return '<nobr>'.$model->settle_date.'</nobr>';
                    }
                    elseif ( isset($model->partial_settlement) ) {
                        return \Yii::t('app','Partial Settlement').'<br>'.$model->partial_settlement." Ft";
                    }
                }
            ],
            [
                'attribute'=>'copy_count',
            ],
            [
                'attribute'=>'storno_invoice_date',
            ],
        ],
    ]); ?>
    </div>
    </div>
</div>
