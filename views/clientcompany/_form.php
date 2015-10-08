<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ClientCompany */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-company-form">

    <?php $form = ActiveForm::begin([
        'action' => isset($companymodel->id)?Url::to(['clientcompany/update','id'=>$companymodel->id]):Url::to(['clientcompany/create']),
    ]); ?>

    <div class="well">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($companymodel, 'company_name')->textInput(['maxlength' => 255]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($companymodel, 'company_pcode')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-md-2">  
                <?= $form->field($companymodel, 'company_city')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($companymodel, 'company_address')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($companymodel, 'company_phone')->textInput(['maxlength' => 255]) ?>
            </div>
        </div>
        
        <div class="form-group">
            <?= $form->field($companymodel, 'client_id')->hiddenInput(['value' => isset($model)?$model->id:$companymodel->client_id])->label(false) ?>
            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
        </div>
    
    </div>

<?php ActiveForm::end(); ?>

</div>
