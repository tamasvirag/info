<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Client;
use app\models\User;
use app\models\Status;
use app\models\Dealer;
use app\models\District;
use app\models\DistrictSearch;
use app\models\PaymentMethod;
use app\models\News;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\helpers\StringHelper;
use kartik\select2\Select2;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'News');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'News',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php $form = ActiveForm::begin([
                                        'options'=>['id'=>'search-news'],
                                        'method'=>'get',
                                    ] ); ?>
    <div class="well">
        <div class="row">
            <div class="col-md-2"><?= $form->field($searchModel, 'name')->textInput(['maxlength' => 255]) ?></div>
            <div class="col-md-2"><?= $form->field($searchModel, 'client_id')->widget( Select2::classname(), [
                                            'data' => ArrayHelper::map( Client::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                                            'language' => 'hu',
                                            'options' => ['placeholder' => Yii::t('app','please choose')],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                        ]); ?></div>

            <div class="col-md-2">
                <?= $form->field($searchModel, 'dealer_id')->widget( Select2::classname(), [
                        'data' => ArrayHelper::map( Dealer::find()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name' ),
                        'language' => 'hu',
                        'options' => ['placeholder' => Yii::t('app','please choose')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($searchModel, 'district_id')->widget( Select2::classname(), [
                        'data' => ArrayHelper::map( DistrictSearch::find()->where('parent_id IS NOT NULL')->orderBy( 'area_id ASC, parent_id ASC, name ASC' )->all(), 'id', 'fullLabel' ),
                        'language' => 'hu',
                        'options' => ['placeholder' => Yii::t('app','please choose')],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
            </div>
            <div class="col-md-3"><?= $form->field($searchModel, 'distribution_date_from')->widget(
                                                DateRangePicker::classname(), [
                                                        'model' => $searchModel,
                                                        'name' => 'distribution_date_from',
                                                        'value' => $searchModel->distribution_date_from,
                                                        'nameTo' => StringHelper::basename($searchModel::className()).'[distribution_date_to]',
                                                        'attributeTo' => 'distribution_date_to',
                                                        'valueTo' => $searchModel->distribution_date_to,
                                                        'language' => 'hu',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd'
                                                            ]
                                                    ]
                                                    ); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= $form->field($searchModel, 'status_id')->dropDownList( ArrayHelper::map( Status::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?>
            </div>
            <div class="col-md-2"><?= $form->field($searchModel, 'payment_method_id')->dropDownList( ArrayHelper::map( PaymentMethod::find()->all(), 'id', 'name' ), ['prompt' => '']  ) ?></div>
            <div class="col-md-2">
                <?= $form->field($searchModel, 'user_id')->widget( Select2::classname(), [
                    'data' => ArrayHelper::map( User::find()->orderBy(['full_name' => SORT_ASC])->all(), 'id', 'full_name' ),
                    'language' => 'hu',
                    'options' => ['placeholder' => Yii::t('app','please choose')],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>

            <div class="col-md-3"><?= $form->field($searchModel, 'invoice_date_from')->widget(
                                                DateRangePicker::classname(), [
                                                        'model' => $searchModel,
                                                        'name' => 'invoice_date_from',
                                                        'value' => $searchModel->invoice_date_from,
                                                        'nameTo' => StringHelper::basename($searchModel::className()).'[invoice_date_to]',
                                                        'attributeTo' => 'invoice_date_to',
                                                        'valueTo' => $searchModel->invoice_date_to,
                                                        'language' => 'hu',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd'
                                                            ]
                                                    ]
                                                    ); ?>
            </div>
            <div class="col-md-3"><?= $form->field($searchModel, 'settle_date_from')->widget(
                                                DateRangePicker::classname(), [
                                                        'model' => $searchModel,
                                                        'name' => 'settle_date_from',
                                                        'value' => $searchModel->settle_date_from,
                                                        'nameTo' => StringHelper::basename($searchModel::className()).'[settle_date_to]',
                                                        'attributeTo' => 'settle_date_to',
                                                        'valueTo' => $searchModel->settle_date_to,
                                                        'language' => 'hu',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd'
                                                            ]
                                                    ]
                                                    ); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <p>
    <strong><?=\Yii::t('app','newscount_all')?>:</strong> <span><?=$newscount_total?></span> db<br>
    <strong><?=\Yii::t('app','Net Revenue')?>:</strong> <span><?=$net_revenue_total?></span> Ft<br>
    <strong><?=\Yii::t('app','Cost')?>:</strong> <span><?=$cost?></span> Ft<br>
    </p>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'tableOptions'=>['class'=>'table table-simple table-bordered'],
        'dataProvider' => $dataProvider,
        'layout'=>'{summary}{pager}{items}{pager}',
        'columns' => [
            [
                'attribute' => 'name',
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return HTML::a( HTML::encode( $model->name ),['update', 'id'=>$model->id] );
                }
            ],

            [
                'attribute' => 'client_id',
                'format'    => 'raw',
                'value'     => 'clientLabel',
                'options'   => ['width'=>'15%'],
            ],

            [
                'attribute' => 'newsCount',
                'format'    => 'raw',
                'value'     => 'newsCount',
                'filter'    => false
            ],

            [
                'attribute' => 'overall_price',
                'format'    => 'raw',
                'value'     => 'overall_price',
                'filter'    => false
            ],

            [
                'attribute' => 'payment_method_id',
                'format'    => 'raw',
                'value'     => 'paymentMethodLabel',
                'filter'    => ArrayHelper::map( PaymentMethod::find()->all(), 'id', 'name' )
            ],

            [
                'label'     => \Yii::t('app','distribution_date_abb'),
                'attribute' =>'distribution_date',
            ],

            [
                'attribute' => 'status_id',
                'format'    => 'raw',
                'value'     => 'statusLabel',
                'filter'    => ArrayHelper::map( Status::find()->all(), 'id', 'name' )
            ],

            [
                'label'     => \Yii::t('app','invoice_date_abb'),
                'attribute' =>'invoice_date',
            ],

            [
                'label'     => \Yii::t('app','settle_date_abb'),
                'attribute' =>'settle_date',
            ],

            [
                'attribute' => 'user_id',
                'format'    => 'raw',
                'value'     => 'userLabel',
                'options'   => ['width'=>'10%'],
            ],
            [
                'attribute' => null,
                'format'    => 'raw',
                'value'     => function( $model ) {
                    return HTML::a( HTML::encode( Yii::t('app','create_from_this') ),['createfrom', 'id'=>$model->id], ['data-confirm'=>\Yii::t('app','confirm_create_from_this')] );
                }
            ],

            [
                'class'     => 'yii\grid\ActionColumn',
                'template'  => '{update} {delete}',
                'buttons'   => [
                    'delete' => function ($url, $model, $key) {
                        if ($model->status_id !== News::STATUS_INVOICED) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], ['data-confirm'=>\Yii::t('app','Are you sure you want to delete this item?')] );
                        }
                        return null;
                    },
                ],
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>

</div>
