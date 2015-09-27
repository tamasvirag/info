<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Office;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DealerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Dealers');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dealer-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Dealer',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
        'columns' => [            
            [
                'attribute' => 'name',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( $model->name ),['update', 'id'=>$model->id] );
                }
            ],
            
            'address',
            'phone',
            'email',
            'comment:ntext',
            
            [
                'attribute' => 'office_id',
                'format'    => 'raw',
                'value'     => 'officeLabel',
                'filter'    => ArrayHelper::map( Office::find()->all(), 'id', 'name' )
            ],
             
            [
                'attribute' => \Yii::t('app','districts'),
                'format' => 'raw',
                'value' => 'districtsLabel',
            ],

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
