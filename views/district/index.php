<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Area;
use app\models\Dealer;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\DistrictSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Districts');
$this->params['breadcrumbs'][] = $this->title;

$dealerData = ArrayHelper::map( Dealer::find()->all(), 'id', 'name' );

?>
<div class="district-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'District',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
        'layout' => '{items}',
        'columns' => [

            [
                'attribute' => 'area_id',
                'format'    => 'raw',
                'value' => 'areaLabel',
                'filter' => ArrayHelper::map( Area::find()->all(), 'id', 'name' ),
            ],
            [
                'attribute' => 'name',
                'format'    => 'raw',
                'value' => function( $model ) {
                    return HTML::a( HTML::encode( $model->name ),['update', 'id'=>$model->id] );
                }
            ],
            [
                'attribute' => 'amount',
                'filter' => false,
            ],
            [
                'attribute' => 'block',
                'filter' => false,
            ],
            [
                'attribute' => 'block_price',
                'filter' => false,
            ],
            [
                'attribute' => 'block_price_real',
                'filter' => false,
            ],
            [
                'attribute' => 'house',
                'filter' => false,
            ],
            [
                'attribute' => 'house_price',
                'filter' => false,
            ],
            [
                'attribute' => 'house_price_real',
                'filter' => false,
            ],
            
            [
                'attribute' => 'dealer_id',
                'format'    => 'raw',
                'value'     => function($model,$id) use ($dealerData) {
                                return Select2::widget([
                                    'name' => 'dealer_id['.$id.']',
                                    'value' => $model->dealer_id,
                                    'data' => $dealerData,
                                    'language' => 'hu',
                                    'options' => ['placeholder' => Yii::t('app','please choose')],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'pluginEvents' => [
                                        'change' => "
                                            function() {
                                                
                                                //console.log( this.value );
                                                
                                                $.ajax({
                                                  type: 'POST',
                                                  url: '".Url::to(['district/updatedealerid','id'=>$id])."',
                                                  data: {'dealer_id' : this.value},
                                                  success: function(data){
                                                    //console.log( data.success );
                                                  },
                                                  dataType: 'json'
                                                });
                                                   
                                            }"
                                    ],
                                ]);                            
                            },
                            
                'filter' => $dealerData
            ],

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>
