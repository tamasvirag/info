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

$this->title = Yii::t('app', 'Invoices');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Invoice',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php $form = ActiveForm::begin([
                                        'options'=>['id'=>'search-invoice'],
                                        'method'=>'get',
                                    ] ); ?>
    <div class="well">
        <div class="row">
            <div class="col-md-2"><?= $form->field($searchModel, 'invoice_number')->textInput(['maxlength' => 255]) ?></div>
            <div class="col-md-2"><?= $form->field($searchModel, 'client_id')->widget( Select2::classname(), [
                                            'data' => ArrayHelper::map( Client::find()->all(), 'id', 'name' ),
                                            'language' => 'hu',
                                            'options' => ['placeholder' => Yii::t('app','please choose')],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ]); ?></div>
            
            
        </div>
        <div class="row">
            
            <div class="col-md-2"><?= $form->field($searchModel, 'payment_method_id')->dropDownList( ArrayHelper::map( PaymentMethod::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?></div>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'user_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( User::find()->all(), 'id', 'full_name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            
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
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
//        'filterRowOptions' => ['class' => 'filters'],
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
                'attribute'=>'invoice_date',
                'filter' => DateRangePicker::widget([
                        'name' => StringHelper::basename($searchModel::className()).'[invoice_date_from]',
                        'value' => $searchModel->invoice_date_from,
                        'nameTo' => StringHelper::basename($searchModel::className()).'[invoice_date_to]',
                        'valueTo' => $searchModel->invoice_date_to,
                        'language' => 'hu',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                            ]
                    ]),
            ],
            
            [
                'attribute'=>'settle_date',
                'filter' => DateRangePicker::widget([
                        'name' => StringHelper::basename($searchModel::className()).'[settle_date_from]',
                        'value' => $searchModel->settle_date_from,
                        'nameTo' => StringHelper::basename($searchModel::className()).'[settle_date_to]',
                        'valueTo' => $searchModel->settle_date_to,
                        'language' => 'hu',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                            ]
                    ]),
            ],
            
            [
                'attribute' => 'user_id',
                'format'    => 'raw',
                'value' => 'userLabel',
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[user_id]',
                                'value' => $searchModel->user_id,
                                'data' => ArrayHelper::merge([''=>''], ArrayHelper::map( User::find()->all(), 'id', 'full_name' )),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'10%'],
            ],
            
            ['class' => 'yii\grid\ActionColumn','template'=>'{view}'],
        ],
    ]); ?>

</div>
