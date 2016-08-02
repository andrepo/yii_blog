<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = "Instructions on how to reset your account password";
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    Hi there <?= $model->first_name ?>,
    </p>
    
    <p>
    We are sorry to hear you are having trouble logging into your account.
    </p>
    
    <p>
    To set a new password for your account click <a href="<?= $model->getResetPasswordUrl() ?>">here</a> or copy and paste the following URL on your favorite browser:
    <br/>
    <?= $model->getResetPasswordUrl() ?>
    </p>
    
    <p>
    Thanks a lot and look forward to seeing your articles.
    </p>
    
    <p>
    <strong>Important:</strong> If you didn't request a password reset please report this to <?= Yii::$app->params['adminEmail'] ?>.
    </p>
    
    <p>
    Best,
    <br/>
    Crossover News
    </p>

</div>
