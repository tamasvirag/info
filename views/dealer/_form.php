<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Dealer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dealer-form container">

    <?php $form = ActiveForm::begin(); ?>
<div class="well">
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'birth')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'taxnumber')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'tajnumber')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'comment')->textarea(['rows' => 2]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'helpers')->textarea(['rows' => 2]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'payment_method')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'other_cost')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-2">
            <strong><?=\Yii::t('app', 'districts')?></strong>
        </div>
        <div class="col-md-2">
            <?=$model->districtsLabel?>
        </div>
    </div>
    
</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
