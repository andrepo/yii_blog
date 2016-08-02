<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Set account password';
$this->params['breadcrumbs'][] = ['label' => 'Account', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="user-password-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ( $password_set ) : ?>
        <div class="alert alert-success">
            Password set successfully! You can now login by clicking <a href="<?= Url::to(['site/login']) ?>">here</a>.
        </div>
    <?php else : ?>
        <?php $form = ActiveForm::begin(); ?>
    
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    
        <?= $form->field($model, 'password_confirm')->passwordInput(['maxlength' => true]) ?>
    
        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>
    
        <?php ActiveForm::end(); ?>
    <?php endif ?>

</div>
