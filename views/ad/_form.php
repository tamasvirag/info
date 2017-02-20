<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\User;
use app\models\Client;
use app\models\Office;
use app\models\Category;
use app\models\HighlightType;
use app\models\AdType;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\StringHelper;
use app\assets\ClientAsset;
use app\assets\AdAsset;


/* @var $this yii\web\View */
/* @var $model app\models\Ad */
/* @var $form yii\widgets\ActiveForm */
?>

<?php

/*
 * highlight types for js
 */
$highlightTypes = HighlightType::find()->all();
$type = [];
foreach ($highlightTypes as $ht) {
    $types[] = $ht->id." :{
                id      : ".$ht->id.",
                name    : '".$ht->name."',
                amount  : ".$ht->amount.",
                type    : '".$ht->type."',
               }";
}
$this->registerJs('var highlightTypes = {'.implode(",", $types)."};", $this::POS_HEAD, 'my-inline-js');
ClientAsset::register($this);
AdAsset::register($this);
?>

<div class="ad-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="well ad-client-select-well">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'client_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( Client::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'nameWithAddress' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-2">
                <?= Html::button(
                    Yii::t('app','Update client'),
                    [
                        'value' => Url::to(['client/update','id'=>2]),
                        'title' => Yii::t('app','Update client'),
                        'class' => 'showModalButton btn btn-primary mt-21',
                        'id'    => 'client-update-button',
                        //'style' => 'display:none',
                    ]
                ); ?>
            </div>
            <div class="col-md-2">
                <?= Html::button(
                    Yii::t('app','Add new client'),
                    [
                        'value' => Url::to(['client/create']),
                        'title' => Yii::t('app','Add new client'),
                        'class' => 'showModalButton btn btn-success mt-21'
                    ]
                ); ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>


    <?php Pjax::begin(['options'=>['id'=>'client-ads-pjax', 'style' => 'display:none']]); ?>
    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterPosition'   => GridView::FILTER_POS_HEADER,
        'layout'=>'{summary}{pager}{items}',
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
                                'options' => ['placeholder' => Yii::t('app','please choose'), 'id' => 'client-ads-gridview-client-select'],
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
                'attribute' => 'net_price',
                'format'    => 'raw',
                'value'     => 'net_price',
                'filter'    => false
            ],
            [
                'attribute' => 'gross_price',
                'format'    => 'raw',
                'value'     => 'gross_price',
                'filter'    => false
            ],
            'publish_date',
            [
                'attribute' => null,
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return HTML::a( HTML::encode( Yii::t('app','create_ad_from_this') ),['get','id'=>$model->id], ['data-ad_id'=>$model->id, 'class'=>'create-ad-from-this'] );
                }
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>



    <div id="ad-form" style="display:none">
    <?php $form = ActiveForm::begin([
            'id' => 'new-ad-form'
        ]); ?>
    <div class="well" id="ad-details">
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'office_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( Office::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'category_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( Category::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'user_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( User::find()->orderBy(['full_name' => SORT_ASC])->all(), 'id', 'name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
            </div>
            <div class="col-md-1">
                <?= $form->field($model, 'words')->textInput(['readonly' => true]) ?>
                <?= $form->field($model, 'letters')->textInput(['readonly' => true]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'highlight_type_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( HighlightType::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                <?= $form->field($model, 'motto')->textInput() ?>
            </div>
            <div class="col-md-1">
                <?= $form->field($model, 'discount')->textInput() ?>
                <?= $form->field($model, 'business')->checkBox() ?>
            </div>
        </div>

        <?php if ( !$model->isNewRecord ): ?>
            <div class="row">
                <div class="col-md-2">
                    <?=\Yii::t('app','Created')?>:<br><?=$model->created_at?date('Y-m-d H:i:s',$model->created_at):"-"?><br>
                    <?=$model->created_by?$model->createdByLabel:""?><br>
                </div>
                <div class="col-md-2">
                    <?=\Yii::t('app','Updated')?>:<br><?=$model->updated_at?date('Y-m-d H:i:s',$model->updated_at):"-"?><br>
                    <?=$model->updated_by?$model->updatedByLabel:""?><br>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>

</div>