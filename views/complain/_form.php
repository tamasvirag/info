<?php

use yii\helpers\Html;
use app\models\District;
use app\models\Dealer;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Complain */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="complain-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <div class="well">
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'district_id')->widget( Select2::classname(), [
                'data' => ArrayHelper::map( District::find()->orderBy(['area_id' => SORT_ASC,'name' => SORT_ASC])->all(), 'id', 'fullLabel' ),
                'language' => 'hu',
                'options' => ['placeholder' => Yii::t('app','please choose')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'dealer_id')->widget( Select2::classname(), [
                'data' => ArrayHelper::map( Dealer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                'language' => 'hu',
                'options' => ['placeholder' => Yii::t('app','please choose')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'investigation_date')->widget(DatePicker::className(), [
                'language' => 'hu',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                    ]
                ]); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'result')->textarea(['rows' => 2]) ?>
        </div>
        
        <?php if(isset($model->id)): ?>
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



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
