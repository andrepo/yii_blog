<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use dosamigos\tinymce\TinyMce;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'news')->textArea(['rows' => 6]);?>

    <?=
    // Show current photo if one has been uploaded
    ( $model->photo !== null ) ?
        Html::img( $model->photo->poster )
        :
        ''
    ?>
    
    <?= $form->field($upload_model, 'imageFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
