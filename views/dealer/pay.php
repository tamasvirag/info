<?php

use yii\helpers\Html;
use yii\helpers\BaseHtml;
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
use app\models\Office;
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

<?php if ($format == 'html'): ?>


<div class="dealer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    
    <?php $form = ActiveForm::begin([
                                        'options'=>['id'=>'dealer-pay-form'], // , 'target'=>'_blank'
                                        'method'=>'post',
                                    ] ); ?>
    <div class="well">
    
        <?php foreach( ArrayHelper::map( Office::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ) as $office_id => $office_name ): ?>
        <div class="row">
            <div class="col-md-12 dealers">
                <p>
                <?= Html::a( $office_name, [''], ['class' => 'dealer-office-select', 'data-office_id' => $office_id] ) ?>
                <?= BaseHtml::checkboxList(
                        'dealers',
                        isset(Yii::$app->request->bodyParams['dealers'])?Yii::$app->request->bodyParams['dealers']:null,
                        ArrayHelper::map( Dealer::find()->where('office_id = '.$office_id)->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                        ['class'=>'dealer-office-'.$office_id]
                )?>
                </p>
            </div>
        </div>
        <?php endforeach; ?>
        
        <div class="row">
            <div class="col-md-12 dealers">
                <p>
                <?= Html::a( \Yii::t('app','Without office'), [''], ['class' => 'dealer-office-select', 'data-office_id' => ''] ) ?>
                <?= BaseHtml::checkboxList(
                        'dealers',
                        isset(Yii::$app->request->bodyParams['dealers'])?Yii::$app->request->bodyParams['dealers']:null,
                        ArrayHelper::map( Dealer::find()->where('office_id IS NULL ')->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                        ['class'=>'dealer-office-']
                )?>
                </p>
            </div>
        </div>
        
        <hr>
        <div class="row">
            <div class="col-md-3">
                <p><strong><?=\Yii::t('app','Distribution Date')?></strong></p>
            <?= DateRangePicker::widget([            
                                        'name' => 'distribution_date_from',
                                        'value' => isset(Yii::$app->request->bodyParams['distribution_date_from'])?Yii::$app->request->bodyParams['distribution_date_from']:null,
                                        'nameTo' => 'distribution_date_to',
                                        'attributeTo' => 'distribution_date_to',
                                        'valueTo' => isset(Yii::$app->request->bodyParams['distribution_date_to'])?Yii::$app->request->bodyParams['distribution_date_to']:null,
                                        'language' => 'hu',
                                        'clientOptions' => [
                                            'autoclose' => true,
                                            'format' => 'yyyy-mm-dd'
                                        ]
                                     ]);?>    
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <input type="hidden" id="dealer-pay-format" name="dealer-pay-format" value=""/>
                <?= Html::Button(Yii::t('app', 'Filter'), ['class' => 'btn btn-primary mt-21', 'id' => 'dealer-pay-filter-btn']) ?>
                <?= Html::Button(Yii::t('app', 'Pdf export'), ['class' => 'btn btn-primary mt-21', 'id' => 'dealer-pay-pdf-btn']) ?>
            </div>
            
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    

<?php endif; ?>




  
    <?php if( count($dataset) ): ?>
    <?php foreach( $dataset as $dealerdata ): ?>
    
    <?php
        $summa          = $dealerdata['summa'];
        $change         = $dealerdata['change'];
        $dataProvider   = $dealerdata['dataProvider'];
        $dealer         = $dealerdata['dealer'];
    ?>
    
    <h5><strong><?=$dealer->name?></strong></h5>
    <?=\Yii::t('app','All')?>: <strong><?=$summa?> Ft</strong>
    
    <?php if ($dataProvider->getCount()): ?>
    
    <?php if(count($change)): ?>
    <table class="table">
    <thead>
    <tr><th colspan="2"><?=\Yii::t('app','Banknotes')?>:</th></tr>
    </thead>
    
    <?php foreach( $change as $note => $count ): ?>
        <tr><td width="15%"><?=$note?> Ft</td><td><?=$count?> db</td></tr>
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
                'enableSorting' => false,
            ],
            [
                'label'     => \Yii::t('app','Distribution Date'),
                'attribute' => 'distribution_date',
                'enableSorting' => false,
            ],
            [
                'label'     => \Yii::t('app','Districts'),
                'attribute' => 'district_name',
                'enableSorting' => false,
            ],
            
            [
                'label'     => \Yii::t('app','Block'),
                'attribute' => 'block',
                'enableSorting' => false,
            ],
            [
                'label'     => \Yii::t('app','Block Price real'),
                'attribute' => 'block_price_real',
                'enableSorting' => false,
            ],
            [
                'label'     => \Yii::t('app','Block Price All'),
                'attribute' => 'block_all',
                'enableSorting' => false,
            ],
            
            [
                'label'     => \Yii::t('app','House'),
                'attribute' => 'house',
                'enableSorting' => false,
            ],
            [
                'label'     => \Yii::t('app','House Price real'),
                'attribute' => 'house_price_real',
                'enableSorting' => false,
            ],
            [
                'label'     => \Yii::t('app','House Price All'),
                'attribute' => 'house_all',
                'enableSorting' => false,
            ],

        ],
    ]); ?>
    
    <?php endif; ?>
    <hr>
    <?php endforeach; ?>
    <?php endif; ?>
    
    
    <?php if( $alltogether['summa'] != 0 ): ?>
    
    <h5><strong><?=\Yii::t('app','Dealers alltogether')?></strong></h5>
    <?=\Yii::t('app','All')?>: <strong><?=$alltogether['summa']?> Ft</strong>
    
    <table class="table">
    <thead>
    <tr><th colspan="2"><?=\Yii::t('app','Banknotes')?>:</th></tr>
    </thead>
    
    <?php foreach( $alltogether['change'] as $note => $count ): ?>
        <tr><td width="15%"><?=$note?> Ft</td><td><?=$count?> db</td></tr>
    <?php endforeach; ?>
    </table>

    <?php endif; ?>

</div>
