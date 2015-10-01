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
    
    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Complain',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
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
                'filter'    => false,
                'options'   => ['width'=>'25%'],

            ],
            [
                'attribute' => 'dealer_id',
                'format'    => 'raw',
                'value'     => 'dealerLabel',
                'filter'    => false,
                'options'   => ['width'=>'25%'],

            ],
            
            [
                'attribute' => 'created_at',
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return HTML::encode( date('Y-m-d', $model->created_at) );
                },
                'filter'    => false,
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
