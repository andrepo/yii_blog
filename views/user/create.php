<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'User Registration';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ( $account_created ) : ?>
        <p>
            Thanks for registering.
        </p>
        <p>
            In order to get you up and running we need to double check you can receive our emails.
        </p>
        <p>
            To activate your account please check you email <strong>(<?= $model->email ?>)</strong> inbox and click on the link we've sent you.
        </p
        <div class="alert alert-warning">
            <strong>PS:</strong> Please also check your spam folder as the activation email maybe flagged as SPAM by mistake on your inbox!
        </div
    <?php else : ?>
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    <?php endif ?>

</div>
