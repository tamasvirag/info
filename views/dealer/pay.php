<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\Client;
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
/* @var $searchModel app\models\DealerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Pay');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dealers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dealer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    
    <?php $form = ActiveForm::begin([
                                        'options'=>['id'=>'search-news'],
                                        'method'=>'get',
                                    ] ); ?>
    <div class="well">
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($searchModel, 'dealer_id')->widget( Select2::classname(), [
                        'data' => ArrayHelper::map( Dealer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
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
            <div class="col-md-2">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary mt-21']) ?>
            </div>
            
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    
    <h4><?=\Yii::t('app','All')?>: <?=$summa?> Ft</h4>
    
    <?php if ($dataProvider->getCount()): ?>
    
    <?php if(count($change)): ?>
    <table class="table">
    <thead>
    <tr><th colspan="2"><strong><?=\Yii::t('app','Banknotes')?>:</strong></th></tr>
    </thead>
    
    <?php foreach( $change as $note => $count ): ?>
        <tr><td width="10%"><?=$note?> Ft</td><td><?=$count?> db</td></tr>
    <?php endforeach; ?>
    </table>
    <?php endif; ?>
    
    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
        'layout'=>'{pager}{items}{pager}',
        'columns' => [
            [
                'label'     => \Yii::t('app','News'),
                'attribute' => 'news_name',
            ],
            [
                'label'     => \Yii::t('app','Distribution Date'),
                'attribute' => 'distribution_date',
            ],
            [
                'label'     => \Yii::t('app','Districts'),
                'attribute' => 'district_name',
            ],
            
            [
                'label'     => \Yii::t('app','Block'),
                'attribute' => 'block',
            ],
            [
                'label'     => \Yii::t('app','Block Price real'),
                'attribute' => 'block_price_real',
            ],
            [
                'label'     => \Yii::t('app','Block Price All'),
                'attribute' => 'block_all',
            ],
            
            [
                'label'     => \Yii::t('app','House'),
                'attribute' => 'house',
            ],
            [
                'label'     => \Yii::t('app','House Price real'),
                'attribute' => 'house_price_real',
            ],
            [
                'label'     => \Yii::t('app','House Price All'),
                'attribute' => 'house_all',
            ],

        ],
    ]); ?>
    
    <?php endif; ?>

</div>
