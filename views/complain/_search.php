<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use app\models\DistrictSearch;
use app\models\Dealer;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ComplainSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="complain-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <div class="well">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'district_id')->widget( Select2::classname(), [
                        'data' => ArrayHelper::map( DistrictSearch::find()->orderBy( 'area_id ASC, parent_id ASC, name ASC' )->all(), 'id', 'fullLabel' ),
                        'language' => 'hu',
                        'options' => ['placeholder' => Yii::t('app','please choose')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
            </div>
            <div class="col-md-3">        
                <?= $form->field($model, 'dealer_id')->widget( Select2::classname(), [
                        'data' => ArrayHelper::map( Dealer::find()->orderBy( 'name ASC' )->all(), 'id', 'name' ),
                        'language' => 'hu',
                        'options' => ['placeholder' => Yii::t('app','please choose')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'created_at_from')->widget(
                    DateRangePicker::classname(), [
                            'model' => $model,
                            'name' => 'created_at_from',
                            'value' => $model->created_at_from,
                            'nameTo' => StringHelper::basename($model::className()).'[created_at_to]',
                            'attributeTo' => 'created_at_to',
                            'valueTo' => $model->created_at_to,
                            'language' => 'hu',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                                ],
                        ]
                        ); ?>
            </div>
            <div class="col-md-2">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary mt-21']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
