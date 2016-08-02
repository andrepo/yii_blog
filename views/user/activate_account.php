<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Check out your inbox to activate your account';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

   <p>
    Thanks for registering.
   </p>
   <p>
    In order to get you up and running we need to double check you can receive our emails.
   </p>
   <p>
    To activate your account please check you email <strong>(<?= $model->email ?>)</strong> inbox and click on the link we've sent you.
   </p>
    
    <div class="alert alert-warning">
        <strong>PS:</strong> Please also check your spam folder as the activation email maybe flagged as SPAM by mistake on your inbox!
    </div

</div>
