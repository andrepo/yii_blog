<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = "Please validate your email address";
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    Hi there <?= $model->first_name ?>,
    </p>
    
    <p>
    Thanks for registering! Before we let you starting writting away on our portal we just need to make sure you will be able to receive our emails.
    </p>
    
    <p>
    To activate your account now click <a href="<?= $model->getValidationUrl() ?>">here</a> or copy and paste the following URL on your favorite browser:
    <br/>
    <?= $model->getValidationUrl() ?>
    </p>
    
    <p>
    Thanks a lot and look forward to seeing your articles.
    </p>
    
    <p>
    Best,
    <br/>
    Crossover News
    </p>

</div>
