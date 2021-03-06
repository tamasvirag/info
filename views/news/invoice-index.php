<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Client;
use app\models\User;
use app\models\Status;
use app\models\Dealer;
use app\models\District;
use app\models\DistrictSearch;
use app\models\PaymentMethod;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\StringHelper;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Invoicing');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin([
                                        'options'=>['id'=>'search-news'],
                                        'method'=>'get',
                                    ] ); ?>
    <div class="well">
        <div class="row">
            <div class="col-md-2"><?= $form->field($searchModel, 'name')->textInput(['maxlength' => 255]) ?></div>
            <div class="col-md-2"><?= $form->field($searchModel, 'client_id')->widget( Select2::classname(), [
                                            'data' => ArrayHelper::map( Client::find()->all(), 'id', 'name' ),
                                            'language' => 'hu',
                                            'options' => ['placeholder' => Yii::t('app','please choose')],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ]); ?></div>
            
            <div class="col-md-2">
                <?= $form->field($searchModel, 'dealer_id')->widget( Select2::classname(), [
                        'data' => ArrayHelper::map( Dealer::find()->all(), 'id', 'name' ),
                        'language' => 'hu',
                        'options' => ['placeholder' => Yii::t('app','please choose')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'district_id')->widget( Select2::classname(), [
                        'data' => ArrayHelper::map( DistrictSearch::find()->where('parent_id IS NOT NULL')->orderBy( 'area_id ASC, parent_id ASC, name ASC' )->all(), 'id', 'fullLabel' ),
                        'language' => 'hu',
                        'options' => ['placeholder' => Yii::t('app','please choose')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
            </div>
            <div class="col-md-3"><?= $form->field($searchModel, 'distribution_date_from')->widget(
                                                DateRangePicker::classname(), [
                                                        'model' => $searchModel,
                                                        'name' => 'distribution_date_from',
                                                        'value' => $searchModel->distribution_date_from,
                                                        'nameTo' => StringHelper::basename($searchModel::className()).'[distribution_date_to]',
                                                        'attributeTo' => 'distribution_date_to',
                                                        'valueTo' => $searchModel->distribution_date_to,
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
                <?= $form->field($searchModel, 'status_id')->dropDownList( ArrayHelper::map( Status::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?>
            </div>
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
                'attribute' => 'name',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( $model->name ),['update', 'id'=>$model->id] );
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
                'attribute'=>'distribution_date',
                'filter' => DateRangePicker::widget([
                        'name' => StringHelper::basename($searchModel::className()).'[distribution_date_from]',
                        'value' => $searchModel->distribution_date_from,
                        'nameTo' => StringHelper::basename($searchModel::className()).'[distribution_date_to]',
                        'valueTo' => $searchModel->distribution_date_to,
                        'language' => 'hu',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                            ]
                    ]),
            ],
            
            [
                'attribute' => 'status_id',
                'format'    => 'raw',
                'value' => 'statusLabel',
                'filter' => ArrayHelper::map( Status::find()->all(), 'id', 'name' )
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
            [
                'attribute' => Yii::t('app','invoice_normal'),
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( Yii::t('app','invoice_normal') ),['invoicepdf', 'id'=>$model->id, 'type'=>'normal'] ).'<br>'.
                            HTML::a( HTML::encode( Yii::t('app','invoice_copy') ),['invoicepdf', 'id'=>$model->id, 'type'=>'copy'] ).'<br>'.
                            HTML::a( HTML::encode( Yii::t('app','invoice_storno') ),['invoicepdf', 'id'=>$model->id, 'type'=>'storno'] );
                }
            ],
        ],
    ]); ?>

</div>
