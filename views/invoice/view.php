<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->invoice_number;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'invoice_number',
            'invoice_date',
            'invoice_deadline_date',
            'settle_date',
            'paymentMethodLabel',
            'clientLabel',
            
            'price_summa',
            'tax_summa',
            'all_summa',
            
            'storno_invoice_number',
            'storno_invoice_date',
            
            'copy_count',
            'printed',
            
            'userLabel',
            'officeLabel',
            
            'created_at',
        ],
    ]) ?>
    
    <?php if(count($model->invoiceItems)): ?>
        <?php foreach($model->invoiceItems as $item): ?>
            <p><?=Yii::t('app',$item->item_class)?> - <?=$item->model->label?></p>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
