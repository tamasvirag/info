<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\User;
use app\models\PaymentMethod;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="client-form">

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
        <div class="col-md-3">  
            <?= $form->field($model, 'regnumber')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-3">  
            <?= $form->field($model, 'taxnumber')->textInput(['maxlength' => 255]) ?>
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
        <div class="col-md-3">  
            <?= $form->field($model, 'web')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    <hr>
    <p><?=\Yii::t('app','post_address_if')?></p>
    <div class="row">
        <div class="col-md-1">
            <?= $form->field($model, 'post_pcode')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'post_city')->textInput(['maxlength' => 255]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'post_address')->textInput(['maxlength' => 255]) ?>
        </div>
    </div>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>

<?php if (isset($model->id)): ?>

<h3><?=\Yii::t('app','Client companies')?></h3>

<?php if (count($model->clientCompanies)): ?>
    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider'  => $companiesDataProvider,
        'layout'        => '{items}',
        'columns' => [
                'company_name',
                'company_pcode',
                'company_city',
                'company_address',
                'company_phone',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template'=>'{update} {delete}',
                    'buttons' =>
                        [
                            'update' => function ($url, $model, $key) {
                                return Html::a( Yii::t('app','Update'), Url::to(['clientcompany/update','id'=>$model->id]));
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a( Yii::t('app','Delete'), Url::to(['clientcompany/delete','id'=>$model->id]), ['data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),]);
                            },
                        ],
                ],
            ]
        ]
    ); ?>
<?php endif; ?>

<p><strong><?=Yii::t('app','Add')?></strong></p>
<?= $this->render('/clientcompany/_form', [
        'model' => $model,
        'companymodel' => $companymodel,
    ]) ?>



<?php endif; ?>