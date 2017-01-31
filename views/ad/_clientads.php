<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use app\models\Office;
use app\models\Client;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="">

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
        'layout'=>'{summary}{pager}{items}{pager}',
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['width'=>'6%'],
            ],
            [
                'attribute' => 'office_id',
                'format'    => 'raw',
                'value' => 'officeLabel',
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[office_id]',
                                'value' => $searchModel->office_id,
                                'options' => ['placeholder' => Yii::t('app','please choose')],
                                'data' => ArrayHelper::map( Office::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'10%'],
            ],
            [
                'attribute' => 'client_id',
                'format'    => 'raw',
                'value' => 'clientLabel',
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[client_id]',
                                'value' => $searchModel->client_id,
                                'options' => ['placeholder' => Yii::t('app','please choose')],
                                'data' => ArrayHelper::map( Client::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'nameWithAddress' ),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'30%'],
            ],
            [
                'attribute' => 'category_id',
                'format'    => 'raw',
                'value' => 'categoryLabel',
                'filter' => Select2::widget([
                                'name' => StringHelper::basename($searchModel::className()).'[category_id]',
                                'value' => $searchModel->category_id,
                                'options' => ['placeholder' => Yii::t('app','please choose')],
                                'data' => ArrayHelper::map( Category::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                                'language' => 'hu',
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]),
                'options' => ['width'=>'10%'],
            ],
            'description:ntext',

            [
                'class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}',
                'options' => ['width'=>'5%'],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
