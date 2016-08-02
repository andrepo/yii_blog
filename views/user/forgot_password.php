<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ( $model->emailSent === true ): ?>
    
        <div class="alert alert-success">
            An email was sent to <?= $model->email ?> with instructions to reset you password.
        </div>
        
    <?php else: ?>
    
        <?php $form = ActiveForm::begin(); ?>
    
            <?= $form->field($model, 'email', ['errorOptions' => ['class' => 'help-block' ,'encode' => false]]) ?>
            
            <?= $form->field($model, 'verifyCode')->widget(Captcha::className()) ?>
    
            <div class="form-group">
                <?= Html::submitButton('Send', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
    
        <?php ActiveForm::end(); ?>
        
    <?php endif; ?>

</div>
