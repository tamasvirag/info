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
use yii\widgets\Pjax;

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
            <div class="col-md-2"><?= $form->field($searchModel, 'client_id')->widget( Select2::classname(), [
                                            'data' => ArrayHelper::map( Client::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                                            'language' => 'hu',
                                            'options' => ['placeholder' => Yii::t('app','please choose')],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ]); ?></div>

            <div class="col-md-2"><?= $form->field($searchModel, 'payment_method_id')->dropDownList( ArrayHelper::map( PaymentMethod::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?></div>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'created_by')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( User::find()->orderBy(['full_name' => SORT_ASC])->all(), 'id', 'full_name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'office_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( Office::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
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
            <div class="col-md-2">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary mt-21']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <?php // Pjax::begin(); ?>
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
                    if ( $model->invoice_type == Invoice::INVOICE_TYPE_NORMAL ) {
                        return HTML::a( HTML::encode( $model->invoice_number ),['update', 'id'=>$model->id] );
                    }
                    elseif ( $model->invoice_type == Invoice::INVOICE_TYPE_STORNO ) {
                        return HTML::encode( $model->invoice_number.' Storno '.$model->storno_invoice_number );
                    }
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
                'attribute' => 'created_by',
                'format'    => 'raw',
                'value' => 'createdByLabel',
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[created_by]',
                                'value' => $searchModel->created_by,
                                'data' => ArrayHelper::merge([''=>''], ArrayHelper::map( User::find()->all(), 'id', 'full_name' )),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'10%'],
            ],
            [
                'attribute' => 'office_id',
                'format'    => 'raw',
                'value' => 'officeLabel',
                'options' => ['width'=>'10%'],
            ],

            [
                'attribute' => Yii::t('app','invoice_copy'),
                'format'    => 'raw',
                'value' => function( $model ) {
                    if ( $model->invoice_type == Invoice::INVOICE_TYPE_NORMAL ) {
                        return HTML::a( HTML::encode( Yii::t('app','Print Invoice Copy') ),['copy', 'id'=>$model->id, 'type'=>'copy'], ['target' => '_blank', 'data-confirm'=>\Yii::t('app','Are you sure?')] );
                    }
                }
            ],
            [
                'attribute'=>'copy_count',
                'format'    => 'raw',
                'value' => function( $model ) {
                    if ( $model->invoice_type == Invoice::INVOICE_TYPE_NORMAL ) {
                        return $model->copy_count;
                    }
                }
            ],
            [
                'attribute' => Yii::t('app','invoice_storno'),
                'format'    => 'raw',
                'value' => function( $model ) {
                    if ( $model->invoice_type == Invoice::INVOICE_TYPE_NORMAL ) {
                        return HTML::a( HTML::encode( isset($model->storno_invoice_date)?Yii::t('app','Print Storno Invoice Again'):Yii::t('app','Print Storno Invoice') ),['storno', 'id'=>$model->id, 'type'=>'storno'], ['target' => '_blank', 'data-confirm'=>\Yii::t('app','confirm_storno')] );
                    }
                }
            ],
            [
                'attribute'=>'storno_invoice_date',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update}',
                'buttons' => [
                    'update' => function ($url, $model, $key) {
                        if ($model->invoice_type === Invoice::INVOICE_TYPE_NORMAL) {
                            return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id]);
                        }
                        else {
                            return null;
                        }
                    },
                ],
            ],
        ],
    ]); ?>
    <?php //Pjax::end(); ?>

</div>
