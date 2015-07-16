<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\web\View;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?=$this->registerJs("var baseUrl = '".Yii::$app->getUrlManager()->getBaseUrl()."';", View::POS_END); ?>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Szuperinfó terjesztés',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-default ', //navbar-fixed-top
                ],
            ]);
            
            if ( !Yii::$app->user->isGuest ) {
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-left'],
                    'items' => [
                        [
                            'label' => \Yii::t('app','news'),
                            'url' => ['/news'],
                            'active' => in_array(Yii::$app->controller->id,['news']),
                            'items' =>  [
                                            ['label' => Yii::t('app', 'Add new'), 'url' => Url::to(['news/create'])],
                                            ['label' => Yii::t('app', 'Listing'), 'url' => Url::to(['news/index'])],
                                            ['label' => Yii::t('app', 'Invoicing cash'), 'url' => Url::to(['invoice/cash'])],
                                            ['label' => Yii::t('app', 'Invoicing transfer'), 'url' => Url::to(['invoice/transfer'])],
                                            ['label' => Yii::t('app', 'Invoices'), 'url' => Url::to(['invoice/index'])]
                                        ]
                            
                        ],
                        
                        ['label' => \Yii::t('app','clients'), 'url' => ['/client'], 'active' => in_array(Yii::$app->controller->id, ['client'])],
                        ['label' => \Yii::t('app','dealers'), 'url' => ['/dealer'], 'active' => in_array(Yii::$app->controller->id, ['dealer'])],
                        ['label' => \Yii::t('app','districts'), 'url' => ['/district'], 'active' => in_array(Yii::$app->controller->id, ['district'])],
                        ['label' => \Yii::t('app','users'), 'url' => ['/user'], 'active' => in_array(Yii::$app->controller->id, ['user'])],
                        
                        /*
                        [
                            'label' => \Yii::t('app','admin'),
                            'items' => [
                                 ['label' => \Yii::t('app','areas'), 'url' => ['/area']],
                                 ['label' => \Yii::t('app','Payment Methods'), 'url' => ['/paymentmethod']],
                                 ['label' => \Yii::t('app','statuses'), 'url' => ['/status']],
                                 ['label' => \Yii::t('app','users'), 'url' => ['/user']],
                            ],
                        ],*/
                        
    
                        Yii::$app->user->isGuest ?
                            ['label' => \Yii::t('app','login'), 'url' => ['/site/login']] :
                            ['label' => \Yii::t('app','logout').' (' . Yii::$app->user->identity->username . ')',
                                'url' => ['/site/logout'],
                                'linkOptions' => ['data-method' => 'post']],
                    ],
                ]);
            }
            
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?php
                foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
                    echo '<div class="alert alert-' . $key . '">' . $message . '</div>';
                }
            ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left"></p>
            <p class="pull-right"></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
