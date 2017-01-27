<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Client;
use app\models\Office;
use app\models\Category;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Ad */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'office_id')->widget( Select2::classname(), [
                'data' => ArrayHelper::map( Office::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                'language' => 'hu',
                'options' => ['placeholder' => Yii::t('app','please choose')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

    <?= $form->field($model, 'client_id')->widget( Select2::classname(), [
                'data' => ArrayHelper::map( Client::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                'language' => 'hu',
                'options' => ['placeholder' => Yii::t('app','please choose')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

    <?= $form->field($model, 'category_id')->widget( Select2::classname(), [
                'data' => ArrayHelper::map( Category::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                'language' => 'hu',
                'options' => ['placeholder' => Yii::t('app','please choose')],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'highlight_type_id')->textInput() ?>

    <?= $form->field($model, 'motto')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'business')->textInput() ?>

    <?= $form->field($model, 'ad_type_id')->textInput() ?>

    <?= $form->field($model, 'words')->textInput() ?>

    <?= $form->field($model, 'letters')->textInput() ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
