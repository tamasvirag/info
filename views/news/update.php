<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'News',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'News'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update').' '.$model->name;
?>

<div class="news-update">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'news_id' => $news_id,
        'model' => $model,
        'dataProvider' => $dataProvider,
    ]) ?>

</div>
