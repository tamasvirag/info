<?php

use yii\db\Query;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\data\ArrayDataProvider;
use app\models\Client;
use app\models\News;
use app\models\NewsSearch;
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

$this->title = Yii::t('app', 'Invoicing transfer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['/news/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php $form = ActiveForm::begin([
                                        'options'=>['id'=>'search-news-transfer'],
                                        'method'=>'get',
                                    ] ); ?>
    <div class="well">
        <div class="row">
            <div class="col-md-1"><b><?=Yii::t('app','Period')?></b></div>
            <div class="col-md-3">
                <?= DateRangePicker::widget([
                    'name' => 'period_from',
                    'value' => $period_from,
                    'nameTo' => 'period_to',
                    'valueTo' => $period_to,
                    'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd'
                        ]
                ]);?>    
            </div>
            <div class="col-md-2">
                <?= Html::submitButton(Yii::t('app', 'Filter'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    
    
    <?php if( count($clients)): ?>
        <?php foreach( $clients as $client ): ?>
            <?= Yii::t('app', 'Client') ?>: <b><?=$client->name?></b>
            
            <?php
            
            $news_q = $client->getNews()
                ->andWhere('payment_method_id='.PaymentMethod::TRANSFER)
                ->andWhere('status_id='.News::STATUS_NEW);
            if ($period_from != "") {
                $news_q->andWhere("distribution_date >='".$period_from."'");
            }
            if ($period_to != "") {
                $news_q->andWhere("distribution_date <='".$period_to."'");
            }
            
            $dataProvider = new ArrayDataProvider([
                'allModels' => $news_q->all(),
                'sort' => [
                    'attributes' => ['news.id', 'news.name'],
                ],
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
            ?>
            
            <?php $form = ActiveForm::begin( [
                    'options'=>['id'=>'news-invoice-'.$client->id, 'target'=>'_blank'],
                    'action'=> Url::to(['invoice/execute']),
                ] ); ?>
            <?= Html::hiddenInput('payment_method_id',PaymentMethod::TRANSFER); ?>
            <?= Html::hiddenInput('preview',1,['id'=>'hidden-field-'.$client->id]); ?>
            
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterPosition'   => GridView::FILTER_POS_HEADER,
                'layout'=>'{pager}{items}{pager}',
                'columns' => [
                    [
                        'header' => '',
                        'class' => 'yii\grid\CheckboxColumn',
                        'checkboxOptions' => function($model, $key, $index, $column) {
                            return [
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
                        'attribute' => 'payment_method_id',
                        'format'    => 'raw',
                        'value' => 'paymentMethodLabel',
                        'options' => ['width'=>'15%'],
                    ],
                    
                    [
                        'attribute'=>'distribution_date',
                        'options' => ['width'=>'15%'],
                    ],
                    
                    [
                        'attribute' => 'status_id',
                        'format'    => 'raw',
                        'value' => 'statusLabel',
                        'options' => ['width'=>'15%'],
                    ],
                    
                    [
                        'attribute'=>'created_at',
                        'format'    => ['date', 'php:Y-m-d'],
                        'options' => ['width'=>'15%'],
                    ],
                                
                    [
                        'attribute' => 'user_id',
                        'format'    => 'raw',
                        'value' => 'userLabel',
                        'options' => ['width'=>'15%'],
                    ],
        
                ],
            ]); ?>
            
            <?php if($dataProvider->count): ?>
                <div class="form-group" id="form-btn-group-<?php echo $client->id; ?>">
                    <?= Html::Button(Yii::t('app', 'Invoice selected preview'), ['class' => 'btn btn-invoice-preview', 'id' => 'btn-invoice-preview-'.$client->id, 'data-client-id'=>$client->id]) ?>
                    <?= Html::Button(Yii::t('app', 'Invoice selected'), ['class' => 'btn btn-primary btn-invoice-submit', 'id' => 'btn-invoice-'.$client->id, 'data-client-id'=>$client->id]) ?>
                </div>
            <?php endif; ?>
            <?php ActiveForm::end(); ?>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</div>