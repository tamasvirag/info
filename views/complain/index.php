<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\District;
use app\models\Dealer;
use yii\helpers\StringHelper;
use kartik\select2\Select2;
use dosamigos\datepicker\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ComplainSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Complains');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complain-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Complain',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'name',
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return HTML::a( HTML::encode( $model->name ),['update', 'id'=>$model->id] );
                },
                'filter'    => false,
                'options' => ['width'=>'15%'],
            ],
            [
                'attribute' => 'district_id',
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return HTML::encode( isset($model->district)?$model->district->fullLabel:"" );
                },
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[district_id]',
                                'value' => $searchModel->district_id,
                                'options' => ['placeholder' => Yii::t('app','please choose')],
                                'data' => ArrayHelper::map( District::find()->orderBy(['area_id' => SORT_ASC,'name' => SORT_ASC])->all(), 'id', 'fullLabel' ),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'25%'],

            ],
            [
                'attribute' => 'dealer_id',
                'format'    => 'raw',
                'value'     => 'dealerLabel',
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[dealer_id]',
                                'value' => $searchModel->dealer_id,
                                'options' => ['placeholder' => Yii::t('app','please choose')],
                                'data' => ArrayHelper::map( Dealer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'25%'],

            ],
            
            [
                'attribute' => 'created_at',
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return HTML::encode( date('Y-m-d', $model->created_at) );
                },
                'filter'    => DateRangePicker::widget([                                    
                                    'model' => $searchModel,
                                    'name' => StringHelper::basename($searchModel::className()).'[created_at_from]',
                                    'value' => $searchModel->created_at_from,
                                    'nameTo' => StringHelper::basename($searchModel::className()).'[created_at_to]',
                                    'attributeTo' => 'created_at_to',
                                    'valueTo' => $searchModel->created_at_to,
                                    'language' => 'hu',
                                    'clientOptions' => [
                                        'autoclose' => true,
                                        'format' => 'yyyy-mm-dd'
                                        ]
                                ])
            ],

            [
                'attribute' => 'investigation_date',
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return HTML::encode( (isset($model->investigation_date))?$model->investigation_date:\Yii::t('app','uninvestigated'));
                },
                'filter'    => false,
                'options' => ['width'=>'10%'],
            ],
            

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
