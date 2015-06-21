<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Dealer */

$this->title = Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Dealer',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Dealers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dealer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
