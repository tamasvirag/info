<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form container">

    <?php $form = ActiveForm::begin(); ?>
    <div class="well">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'full_name')->textInput(['maxlength' => 255]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($model, 'active')->checkbox() ?>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
