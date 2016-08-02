<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '<img src="'.Yii::$app->urlManager->baseUrl.'/img/xo-logo-white.png" style="margin-top: -14px;">',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    $navbar_items = [
        ['label' => 'Home', 'url' => ['/']],
        ['label' => 'RSS Feed', 'url' => ['articles/rss']]
    ];
    
    $navbar_items[] =
        Yii::$app->user->isGuest ?
            ['label' => 'Register', 'url' => ['/user/create']]
            :
            ['label' => 'Write', 'url' => ['/article/create']]
    ;
     
    if ( Yii::$app->user->isGuest === false && Yii::$app->user->identity->id ) {
        $navbar_items[] =
            ['label' => 'My Articles', 'url' => ['/articles/user/'.Yii::$app->user->identity->id]]
        ;
    }
    
    $navbar_items[] =
        Yii::$app->user->isGuest ?
            ['label' => 'Forgot password', 'url' => ['/user/forgotpassword']]
            :
            ['label' => 'Profile', 'url' => ['/user/view/'.Yii::$app->user->identity->id]]
    ;
    
    $navbar_items[] =        
        Yii::$app->user->isGuest ?
            ['label' => 'Login', 'url' => ['/site/login']]
            :
            [
                'label' => 'Logout (' . Yii::$app->user->identity->email . ')',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
            ]
    ;
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $navbar_items,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Crossover <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
