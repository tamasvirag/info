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

$this->title = Yii::t('app', 'Invoicing cash');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['/news/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Yii::t('app', 'Period') ?>: <?=$period_from?> - <?=$period_to?><br><?= Yii::t('app', 'Client') ?>: <?=$client->name?></p>

    <?php $form = ActiveForm::begin( ['options'=>['id'=>'news-invoice-cash', 'target'=>'_blank']] ); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
//        'filterRowOptions' => ['class' => 'filters'],
        'layout'=>'{pager}{items}{pager}',
        'columns' => [
            [
                'header' => '',
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model, $key, $index, $column) use ($news_id) {
                    return [
                        'checked'   => $model->id == $news_id?'checked':'',
                        'value'     => $model['id'],
                    ];
                },
                'contentOptions' => ['width'=>'3%'],
            ],
            [
                'attribute' => 'name',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( $model->name ),['/news/update', 'id'=>$model->id] );
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
                'attribute'=>'created_at',
                'format'    => ['date', 'php:Y-m-d'],
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

        ],
    ]); ?>
    
    <?php if($dataProvider->count): ?>
    
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Invoice selected'), ['class' => 'btn btn-primary']) ?>
    </div>
    <?php endif; ?>
    
    <?php ActiveForm::end(); ?>
    

</div>