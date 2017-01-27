<?php

namespace app\components;

use yii\helpers\url;

class MenuHelper
{
    public static function getAssignedMenu()
    {
        $items              = [];
        $adMenuItems        = [];
        $newsMenuItems      = [];
        $invoiceMenuItems   = [];
        $dealerMenuItems    = [];
        $districtMenuItems  = [];

        //if (\Yii::$app->user->can('adManager')) {
            //$adMenuItems[] = '<li role="separator" class="divider"></li>';
            //$adMenuItems[] = '<li class="dropdown-header">'.\Yii::t('app', 'Ads').'</li>';
            $adMenuItems[] = ['label' => \Yii::t('app', 'Listing'), 'url' => Url::to(['ad/index'])];
            $adMenuItems[] = ['label' => \Yii::t('app', 'Add new'), 'url' => Url::to(['ad/create'])];
        //}
        if (\Yii::$app->user->can('dealerManager')) {
            $dealerMenuItems[] = '<li role="separator" class="divider"></li>';
            $dealerMenuItems[] = '<li class="dropdown-header">'.\Yii::t('app', 'Dealers').'</li>';
            $dealerMenuItems[] = ['label' => \Yii::t('app', 'Add new'), 'url' => Url::to(['dealer/create'])];
            $dealerMenuItems[] = ['label' => \Yii::t('app', 'Listing'), 'url' => Url::to(['dealer/index'])];
            $dealerMenuItems[] = ['label' => \Yii::t('app', 'Pay'), 'url' => Url::to(['dealer/pay'])];
            $dealerMenuItems[] = ['label' => \Yii::t('app','Complain'), 'url' => ['/complain']];
            /*$items[] = [
                'label' => \Yii::t('app','dealers'),
                'url' => ['/dealer'],
                'active' => in_array(\Yii::$app->controller->id,['dealer']),
                'items' => $dealerMenuItems
            ];*/
        }
        if (\Yii::$app->user->can('districtManager')) {
            $districtMenuItems[] = '<li role="separator" class="divider"></li>';
            $districtMenuItems[] = '<li class="dropdown-header">'.\Yii::t('app', 'Districts').'</li>';
            $districtMenuItems[] = ['label' => \Yii::t('app','districts'), 'url' => ['/district'], 'active' => in_array(\Yii::$app->controller->id, ['district'])];
        }
        if (\Yii::$app->user->can('newsManager')) {
            $newsMenuItems[] = '<li class="dropdown-header">'.\Yii::t('app', 'News').'</li>';
            $newsMenuItems[] = ['label' => \Yii::t('app', 'Add new'), 'url' => Url::to(['news/create'])];
            $newsMenuItems[] = ['label' => \Yii::t('app', 'Listing'), 'url' => Url::to(['news/index'])];
            $newsMenuItems[] = ['label' => \Yii::t('app', 'Invoicing cash'), 'url' => Url::to(['invoice/cash'])];
        }
        if (\Yii::$app->user->can('invoiceManager')) {
            $newsMenuItems[] = ['label' => \Yii::t('app', 'Invoicing transfer'), 'url' => Url::to(['invoice/transfer'])];

            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Invoicing cash')." ".strtolower(\Yii::t('app', 'News')), 'url' => Url::to(['invoice/cash'])];
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Invoicing transfer')." ".strtolower(\Yii::t('app', 'News')), 'url' => Url::to(['invoice/transfer'])];
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Invoices'), 'url' => Url::to(['invoice/index'])];
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Invoicing history'), 'url' => Url::to(['invoicegroup/index'])];
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Demand for payment'), 'url' => Url::to(['invoicedemand/index'])];
        }
        if (\Yii::$app->user->can('navInvoiceManager')) {
            $invoiceMenuItems[] = ['label' => \Yii::t('app', 'Invoicing for NAV'), 'url' => Url::to(['invoice/nav-index'])];
        }
        //if (\Yii::$app->user->can('adManager')) {
              $items[] = [
                'label' => \Yii::t('app','Ads'),
                'url' => ['/ad'],
                'active' => in_array(\Yii::$app->controller->id,['ad']),
                'items' => array_merge($adMenuItems),
            ];
        //}
        if (\Yii::$app->user->can('invoiceManager')||\Yii::$app->user->can('newsManager')||\Yii::$app->user->can('dealerManager')||\Yii::$app->user->can('districtManager')) {
            $items[] = [
                'label' => \Yii::t('app','News'),
                'url' => ['/news'],
                'active' => in_array(\Yii::$app->controller->id,['news']),
                'items' => array_merge($newsMenuItems,$dealerMenuItems,$districtMenuItems),
            ];
        }
        if (\Yii::$app->user->can('invoiceManager')) {
            $items[] = [
                'label' => \Yii::t('app','Invoicing'),
                'url' => ['/invoice'],
                'active' => in_array(\Yii::$app->controller->id,['invoice','invoicegroup','invoicedemand']),
                'items' =>  $invoiceMenuItems,
            ];
        }
        if (\Yii::$app->user->can('navInvoiceManager')) {
            $items[] = [
                'label' => \Yii::t('app','Invoicing for NAV'),
                'url' => ['/invoice/nav-index'],
                'active' => in_array(\Yii::$app->controller->id,['invoice'])&&in_array(\Yii::$app->controller->action->id,['nav-index']),
            ];
        }
        if (\Yii::$app->user->can('clientManager')) {
            $items[] = ['label' => \Yii::t('app','clients'), 'url' => ['/client'], 'active' => in_array(\Yii::$app->controller->id, ['client'])];
        }
        /*if (\Yii::$app->user->can('dealerControlManager')) {
            $items[] = ['label' => \Yii::t('app','Complain'), 'url' => ['/complain'], 'active' => in_array(\Yii::$app->controller->id, ['complain'])];
        }*/
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
