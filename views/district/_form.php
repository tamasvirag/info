<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Area;
use app\models\Dealer;
use app\models\District;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\District */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="district-form">

    <?php $form = ActiveForm::begin(); ?>
<div class="well">
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'area_id')->dropDownList( ArrayHelper::map( Area::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'amount')->textInput(['readonly' => true]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'block')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'block_price')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'block_price_real')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'house')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'house_price')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'house_price_real')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'dealer_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( Dealer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'parent_id')->dropDownList( ArrayHelper::map( District::find()->where( 'parent_id IS NULL' )->all(), 'id', 'fullLabel' ), ['prompt' => '']  ) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'deleted')->dropDownList( ArrayHelper::map( [ [ 'id' => 0, 'name' => \Yii::t('app','active') ], [ 'id' => 1, 'name' => \Yii::t('app','deleted') ] ], 'id', 'name' ) ) ?>
        </div>
    </div>
    
</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
