<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;
use app\models\PaymentMethod;
use app\models\Client;

?>

<div class="dealer-form">

<?php $form = ActiveForm::begin(); ?>
<div class="well">
    <div class="row">
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Invoice Number')?>:</strong><br><?=$model->invoice_number?>
        </div>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Invoice Date')?>:</strong><br><?=$model->invoice_date?>
        </div>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Invoice Deadline Date')?>:</strong><br><?=$model->invoice_deadline_date?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'settle_date')->widget(DatePicker::className(), [
                'language' => 'hu',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                    ]
                ]); ?>
        </div>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Payment Method')?>:</strong><br><?=$model->paymentMethodLabel?>
        </div>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Client')?>:</strong><br><?=$model->clientLabel?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-1">
            <strong><?=\Yii::t('app','Price')?>:</strong><br><?=$model->price_summa?> Ft
        </div>
        <div class="col-md-1">
            <strong><?=\Yii::t('app','Tax')?>:</strong><br><?=$model->tax_summa?> Ft
        </div>
        <div class="col-md-1">
            <strong><?=\Yii::t('app','All')?>:</strong><br><?=$model->all_summa?> Ft
        </div>
        
        <div class="col-md-2 col-md-offset-5">
            <strong><?=\Yii::t('app','Created')?>:</strong><br><?=$model->created_at?date('Y-m-d H:i:s',$model->created_at):"-"?><br>
            <?=$model->created_by?$model->createdByLabel:""?><br>
        </div>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Updated')?>:</strong><br><?=$model->updated_at?date('Y-m-d H:i:s',$model->updated_at):"-"?><br>
            <?=$model->updated_by?$model->updatedByLabel:""?><br>
        </div>
    </div>
    </div>

    <div class="well">
    <div class="row">
        <div class="col-md-2">
            <?= HTML::a( HTML::encode( Yii::t('app','Print Storno Invoice') ),['storno', 'id'=>$model->id, 'type'=>'storno'], ['target' => '_blank', 'data-confirm'=>\Yii::t('app','confirm_storno'), 'class' => 'btn btn-block btn-primary inline-block'] ) ?>
        </div>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Storno Invoice Number')?>:</strong><br><?=$model->storno_invoice_number?$model->storno_invoice_number:"-"?>
        </div>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Storno Invoice Date')?>:</strong><br><?=$model->storno_invoice_date?$model->storno_invoice_date:"-"?>
        </div>
    </div>
    </div>
    
    <div class="well">    
    <div class="row">
        <div class="col-md-2">
            <?= HTML::a( HTML::encode( Yii::t('app','Print Invoice Copy') ),['copy', 'id'=>$model->id, 'type'=>'copy'], ['target' => '_blank', 'data-confirm'=>\Yii::t('app','confirm'), 'class' => 'btn btn-block btn-primary inline-block'] ) ?>
        </div>
        <div class="col-md-2">
            <strong><?=\Yii::t('app','Copy Count')?>:</strong><br><?=$model->copy_count?$model->copy_count:"-"?>
        </div>
    </div>
    
</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
