<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use app\models\Client;
use app\models\User;
use app\models\Status;
use app\models\PaymentMethod;
use app\models\News;
use app\models\NewsDistrict;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin( ['options'=>['id'=>'edit-news-districts']] ); ?>

    <div class="well">
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'client_id')->widget( Select2::classname(), [
                'data' => ArrayHelper::map( Client::find()->all(), 'id', 'name' ),
                'language' => 'hu',
                'options' => ['placeholder' => Yii::t('app','please choose')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'user_id')->widget( Select2::classname(), [
                'data' => ArrayHelper::map( User::find()->all(), 'id', 'full_name' ),
                'language' => 'hu',
                'options' => ['placeholder' => Yii::t('app','please choose')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>
        </div>    
    </div>
    

    <div class="row">        
        <div class="col-md-2">
            <?= $form->field($model, 'payment_method_id')->dropDownList( ArrayHelper::map( PaymentMethod::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'distribution_date')->widget(DatePicker::className(), [
                'language' => 'hu',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                    ]
                ]); ?>
        </div>
        
        <?php if(isset($model->id)): ?>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Status ID')?></strong><br><?=$model->statusLabel?>
        </div>
        <div class="col-md-2">
            <?=\Yii::t('app','Invoice Date')?>:<br><?=$model->invoice_date?$model->invoice_date:"-"?><br>
            <?=\Yii::t('app','Settle Date')?>:<br><?=$model->settle_date?$model->settle_date:"-"?>
        </div>
        <div class="col-md-2">
            <?=\Yii::t('app','Created')?>:<br><?=$model->created_at?date('Y-m-d H:i:s',$model->created_at):"-"?><br>
            <?=$model->created_by?$model->createdByLabel:""?><br>
        </div>
        <div class="col-md-2">
            <?=\Yii::t('app','Updated')?>:<br><?=$model->updated_at?date('Y-m-d H:i:s',$model->updated_at):"-"?><br>
            <?=$model->updated_by?$model->updatedByLabel:""?><br>
        </div>
        <?php endif; ?>
    </div>
    </div>
    
    <?php if(isset($model->id)): ?>
    
    <h3><?=\Yii::t('app','districts')?></h3>
    <p><strong><?=\Yii::t('app','newscount_all')?>:</strong> <span id="newscount-all"></span></p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout'=>'{items}',
        'rowOptions' => function ($model, $key, $index, $grid){
                            return [
                                'id'    => 'row-'.$model['id'],
                                'class' => $model->parent_id?'child child-'.$model->parent_id:"parent"
                            ];
                        },
        'columns' => [
            [
                'header' => '',
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function($model, $key, $index, $column) use ($news_id) {
                    /**
                     * Set news_id for District
                     */
                    $model->news_id = $news_id;
                    
                    if (isset($model->parent_id)){
                        return [
                            'checked'   => count($model->nD)?'checked':'',
                            'value'     => $model['id'],
                            'class'     => 'visible group-'.$model->parent_id,
                        ];
                    }
                    else {
                        return [
                            'checked'   => count($model->nD)?'checked':'',
                            'value'     => $model['id'],
                            'class'     => 'visible group-parent',
                        ];
                    }
                },
                'contentOptions' => ['width'=>'3%'],
            ],
            [
                'attribute' => 'area_id',
                'format'    => 'raw',
                'value' => function($model, $key) {
                    if (!isset($model->parent_id)) {
                        return '<nobr><a class="accordion" data-id="'.$model['id'].'">+ '.$model->areaLabel.'</a></nobr>';
                    }
                    else {
                        return $model->areaLabel;
                    }
                },
                'filter' => false,
                'contentOptions' => ['width'=>'15%'],
            ],
            [
                'attribute' => 'name',
                'contentOptions' => ['width'=>'12%'],
            ],
            
            [
                'label' => \Yii::t('app','newscount'),
                'attribute' => 'amount',
                'filter' => false,
                'format' => 'raw',
                'value' => function( $model, $id ) {
                    if (isset($model->parent_id)){
                        return "<div class='newscount' id='newscount-".$id."'>".(count($model->nD)?$model->nD[0]->amount:null)."</div>";
                    }
                },
                'contentOptions' => ['width'=>'10%'],
            ],
            [
                'attribute' => 'block',
                'filter' => false,
                'format' => 'raw',
                'value' => function( $model, $id ) {
                    if (isset($model->parent_id)){
                        return HTML::textInput( 'newsDistrict[block]['.$id.']', count($model->nD)?$model->nD[0]->block:null, [
                            'placeHolder'   => $model->block,
                            'class'         => 'form-control newscount-trigger',
                            'id'            => 'block-'.$id,
                        ] );
                    } else {
                        return $model->block;
                    }
                },
                'contentOptions' => ['width'=>'10%'],
            ],
            [
                'attribute' => 'block_price',
                'filter' => false,
                'format' => 'raw',
                'value' => function( $model, $id ) {
                    if (isset($model->parent_id)){
                        return HTML::textInput( 'newsDistrict[block_price]['.$id.']', count($model->nD)?$model->nD[0]->block_price:null, [
                            'placeHolder'   => $model->block_price,
                            'class'         => 'form-control newscount-trigger',
                            'id'            => 'block-price-'.$id,
                        ] );
                    } else {
                        return $model->block_price;
                    }
                },
                'contentOptions' => ['width'=>'10%'],
            ],
            [
                'attribute' => 'block_price_real',
                'filter' => false,
                'format' => 'raw',
                'value' => function( $model, $id ) {
                    if (isset($model->parent_id)){                    
                        return HTML::textInput( 'newsDistrict[block_price_real]['.$id.']', count($model->nD)?$model->nD[0]->block_price_real:null, [
                            'placeHolder'   => $model->block_price_real,
                            'class'         => 'form-control',
                        ] );
                    } else {
                        return $model->block_price_real;
                    }
                },
                'contentOptions' => ['width'=>'10%'],
            ],
            [
                'attribute' => 'house',
                'filter' => false,
                'format' => 'raw',
                'value' => function( $model, $id ) {
                    if (isset($model->parent_id)){
                        return HTML::textInput( 'newsDistrict[house]['.$id.']', count($model->nD)?$model->nD[0]->house:null, [
                            'placeHolder'   => $model->house,
                            'class'         => 'form-control newscount-trigger',
                            'id'            => 'house-'.$id,
                        ] );
                    } else {
                        return $model->house;
                    }
                },
                'contentOptions' => ['width'=>'10%'],
            ],
            [
                'attribute' => 'house_price',
                'filter' => false,
                'format' => 'raw',
                'value' => function( $model, $id ) {
                    if (isset($model->parent_id)){
                        return HTML::textInput( 'newsDistrict[house_price]['.$id.']', count($model->nD)?$model->nD[0]->house_price:null, [
                            'placeHolder'   => $model->house_price,
                            'class'         => 'form-control newscount-trigger',
                            'id'            => 'house-price-'.$id,
                        ] );
                    } else {
                        return $model->house_price;
                    }
                },
                'contentOptions' => ['width'=>'10%'],
            ],
            [
                'attribute' => 'house_price_real',
                'filter' => false,
                'format' => 'raw',
                'value' => function( $model, $id ) {
                    if (isset($model->parent_id)){                    
                        return HTML::textInput( 'newsDistrict[house_price_real]['.$id.']', count($model->nD)?$model->nD[0]->house_price_real:null, [
                            'placeHolder'   => $model->house_price_real,
                            'class'         => 'form-control',
                        ] );
                    } else {
                        return $model->house_price_real;
                    }
                },
                'contentOptions' => ['width'=>'10%'],
            ],
        ],
    ]); ?>
    
    <?php else: ?>
    <p><?= Yii::t('app','districts_after_save'); ?></p>
    <?php endif; ?>
    
    <div class="form-group">
        <?php if (!in_array($model->status_id, [News::STATUS_INVOICED,News::STATUS_SETTLED])): ?>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php endif; ?>
        
        <?php if(isset($model->id)): ?>
        <?= Html::a(Yii::t('app','create_from_this'), ['news/createfrom', 'id' => $model->id], ['class' => 'btn btn-block btn-primary', 'data-confirm' => Yii::t('app', 'confirm_create_from_this')]) ?>
        
            <?php if ($model->payment_method_id == PaymentMethod::CASH && $model->status_id == News::STATUS_NEW ): ?>
            <?= Html::a(Yii::t('app', 'Invoicing'), ['invoice/cash', 'news_id' => $model->id, 'type'=>'normal'], ['class' => 'btn btn-block btn-primary', 'data-confirm' => Yii::t('app', 'confirm_invoicing')]) ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php if ( in_array($model->status_id, [News::STATUS_INVOICED,News::STATUS_SETTLED]) ): ?>
<script>
    disableNews = true;
</script>
<?php endif; ?>















