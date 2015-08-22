<?php

namespace app\components;

use yii\helpers\url;

class MenuHelper
{
    public static function getAssignedMenu()
    {
        $items = [];
        $newsMenuItems = [];
        $invoiceMenuItems = [];
        
        if (\Yii::$app->user->can('newsManager')) {
            $newsMenuItems[] = ['label' => \Yii::t('app', 'Add new'), 'url' => Url::to(['news/create'])];
            $newsMenuItems[] = ['label' => \Yii::t('app', 'Listing'), 'url' => Url::to(['news/index'])];
            $newsMenuItems[] = ['label' => \Yii::t('app', 'Invoicing cash'), 'url' => Url::to(['invoice/cash'])];
            $newsMenuItems[] = ['label' => \Yii::t('app', 'Invoicing transfer'), 'url' => Url::to(['invoice/transfer'])];
        }
        if (\Yii::$app->user->can('invoiceManager')) {
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Invoicing cash')." ".strtolower(\Yii::t('app', 'News')), 'url' => Url::to(['invoice/cash'])];
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Invoicing transfer')." ".strtolower(\Yii::t('app', 'News')), 'url' => Url::to(['invoice/transfer'])];
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Invoices'), 'url' => Url::to(['invoice/index'])];
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Demand for payment'), 'url' => Url::to(['invoicedemand/index'])];
        }
        
        if (\Yii::$app->user->can('invoiceManager')||\Yii::$app->user->can('newsManager')) {
            $items[] = [
                'label' => \Yii::t('app','News'),
                'url' => ['/news'],
                'active' => in_array(\Yii::$app->controller->id,['news']),
                'items' =>  $newsMenuItems,   
            ];
        }
        
        if (\Yii::$app->user->can('invoiceManager')||\Yii::$app->user->can('newsManager')) {
            $items[] = [
                'label' => \Yii::t('app','Invoicing'),
                'url' => ['/invoice'],
                'active' => in_array(\Yii::$app->controller->id,['invoice']),
                'items' =>  $invoiceMenuItems,
            ];
        }
        
        if (\Yii::$app->user->can('clientManager')) {
            $items[] = ['label' => \Yii::t('app','clients'), 'url' => ['/client'], 'active' => in_array(\Yii::$app->controller->id, ['client'])];
        }
        if (\Yii::$app->user->can('dealerManager')) {
            $items[] = ['label' => \Yii::t('app','dealers'), 'url' => ['/dealer'], 'active' => in_array(\Yii::$app->controller->id, ['dealer'])];
        }
        if (\Yii::$app->user->can('districtManager')) {
            $items[] = ['label' => \Yii::t('app','districts'), 'url' => ['/district'], 'active' => in_array(\Yii::$app->controller->id, ['district'])];
        }
        if (\Yii::$app->user->can('userManager')) {
            $items[] = ['label' => \Yii::t('app','users'), 'url' => ['/user'], 'active' => in_array(\Yii::$app->controller->id, ['user'])];
        }
        
        /*
if (\Yii::$app->user->can('admin')) {
            $items[] = [
                'label' => \Yii::t('app','admin'),
                'items' => [
                     ['label' => \Yii::t('app','areas'), 'url' => ['/area']],
                     ['label' => \Yii::t('app','Payment Methods'), 'url' => ['/paymentmethod']],
                     ['label' => \Yii::t('app','statuses'), 'url' => ['/status']],
                     ['label' => \Yii::t('app','users'), 'url' => ['/user']],
                ],
            ];
        }
*/        
        
        $items[] = \Yii::$app->user->isGuest ?
                ['label' => \Yii::t('app','login'), 'url' => ['/site/login']] :
                ['label' => \Yii::t('app','logout').' (' . \Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']];

        
        return $items;
    }
}