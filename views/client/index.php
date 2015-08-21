<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Clients');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                'modelClass' => 'Client',
            ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
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
            
            // 'web',
            // 'regnumber',
            // 'taxnumber',
             'company_name',
            // 'company_pcode',
            // 'company_city',
            // 'company_address',
            // 'contact_name',
            // 'contact_phone',
            
            'pcode',
            'city',
            'address',
            //'post_address',
            [
                'attribute' => 'user_id',
                'format'    => 'raw',
                'value' => 'userLabel',
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[user_id]',
                                'value' => $searchModel->user_id,
                                'options' => ['placeholder' => Yii::t('app','please choose')],
                                'data' => ArrayHelper::map( User::find()->all(), 'id', 'full_name' ),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'10%'],
            ],

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
