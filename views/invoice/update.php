<?php

use yii\helpers\Html;

$this->title = Yii::t('app', 'Invoice') . ' ' . $model->invoice_number;
        
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoice'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->invoice_number;
?>

<div class="news-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
