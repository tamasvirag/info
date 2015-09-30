<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Complain */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Complain',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Complains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="complain-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
