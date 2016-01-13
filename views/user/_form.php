<?php

use yii\helpers\BaseHtml;
use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use yii\bootstrap\ActiveForm;
use app\models\Office;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="well">
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'full_name')->textInput(['maxlength' => 255]) ?>
            </div>
        
            <div class="col-md-2">
                <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-md-2">
                <?= $form->field($model, 'password')->textInput(['maxlength' => 255]) ?>
            </div>
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
            <div class="col-md-2">
                <?= $form->field($model, 'active')->checkbox() ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p><strong><?=\Yii::t('app','Roles')?></strong></p>
                <?php foreach( $allRoles as $roleKey => $role): ?>
                    <?= BaseHtml::checkbox(
                            'roles[]',
                            array_key_exists($roleKey, $userAssignments),
                            ['label' => \Yii::t('app',$roleKey), 'value' => $roleKey]
                        ); ?>
                        <br>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
