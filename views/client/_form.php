<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\PaymentMethod;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form container">

    <?php $form = ActiveForm::begin(); ?>

    <div class="well">
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'payment_method_id')->dropDownList( ArrayHelper::map( PaymentMethod::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1">
            <?= $form->field($model, 'pcode')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'city')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'address')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'post_address')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">  
            <?= $form->field($model, 'web')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-3">  
            <?= $form->field($model, 'regnumber')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-3">  
            <?= $form->field($model, 'taxnumber')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'company_name')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'company_pcode')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">  
            <?= $form->field($model, 'company_city')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'company_address')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'contact_name')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">  
            <?= $form->field($model, 'contact_phone')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'user_id')->dropDownList( ArrayHelper::map( User::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?>
        </div>
    </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
