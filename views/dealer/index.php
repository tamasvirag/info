<?php

use yii\helpers\Html;
use yii\grid\GridView;

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
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            //'id',
            
            [
                'attribute' => 'name',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( $model->name ),['update', 'id'=>$model->id] );
                }
            ],
            
            'address',
            //'birth',
            //'taxnumber',
            // 'tajnumber',
             'phone',
             'email:email',
             'comment:ntext',
             
            [
                'attribute' => \Yii::t('app','districts'),
                'format' => 'raw',
                'value' => 'districtsLabel',
            ],
            
            // 'helpers:ntext',
            // 'payment_method',
            // 'other_cost',

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
