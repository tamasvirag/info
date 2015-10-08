<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ClientCompany */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Client Company',
]) . ' ' . $companymodel->company_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Clients'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $companymodel->company_name];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="client-company-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'companymodel' => $companymodel,
    ]) ?>

</div>
