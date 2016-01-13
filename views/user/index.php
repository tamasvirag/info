<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'User',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'filterPosition'   => GridView::FILTER_POS_HEADER,
        'columns' => [
            [
                'attribute' => 'full_name',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( $model->name ),['update', 'id'=>$model->id] );
                }
            ],

            'username',
            
            [
                'attribute' => 'roles',
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return $model->rolesText;
                },
                'filter'    => false,
            ],
            
            [
                'attribute' => 'office_id',
                'format'    => 'raw',
                'value'     => 'officeLabel',
            ],
            
            [
                'attribute' => 'active',
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return $model->active?Yii::t('app','active'):Yii::t('app','inactive');
                },
                'filter'    => false,
            ],

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
