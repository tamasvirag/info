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

    <?php $form = ActiveForm::begin( [
            'options'=>['id'=>'news-invoice', 'target'=>'_blank'],
            'action'=> Url::to(['invoice/execute']),
        ] ); ?>
    <?= Html::hiddenInput('payment_method_id',PaymentMethod::CASH); ?>
    <?= Html::hiddenInput('preview',1,['id'=>'hidden-field']); ?>
    
    
    
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>'{pager}{items}{pager}',
        'columns' => [
            [
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
                'attribute' => 'newsCount',
                'format'    => 'raw',
                'value' => 'newsCount',
                'filter' => false,
                'options' => ['width'=>'15%'],
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
                'options' => ['width'=>'15%'],
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
                'options' => ['width'=>'15%'],
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
                'options' => ['width'=>'15%'],
            ],

        ],
    ]); ?>
    
    <?php if($dataProvider->count): ?>
    
    <div class="form-group" id="form-btn-group">
        <?= Html::Button(Yii::t('app', 'Invoice selected preview'), ['class' => 'btn btn-invoice-preview', 'id' => 'btn-invoice-preview']) ?>
        <?= Html::Button(Yii::t('app', 'Invoice selected'), ['class' => 'btn btn-primary btn-invoice-submit', 'id' => 'btn-invoice']) ?>
    </div>
    <?php endif; ?>
    
    <?php ActiveForm::end(); ?>
    

</div>