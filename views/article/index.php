<?php

use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

// We can overrite page titles ...
if ( isset($customTitle) && $customTitle != '' ) {
    $this->title = $customTitle;
    $this->params['breadcrumbs'][] = $this->title;
} else {
    // In case we are using this templates on the homepage ...
    if ( Url::current() == (Url::home().'index') ) {
        // Just show a custom title
        $this->title = 'Home';
    } else {
        $this->title = 'Articles';
        $this->params['breadcrumbs'][] = $this->title;
    }    
}

?>
    <?php if ( count($dataProvider->models) == 0 ) : ?>
        <div class="alert alert-info">
            No news yet!?
        </div>
    <?php endif ?>

    <?php foreach($dataProvider->models as $model) : ?>
    <div class="row">
    
        <div class="col-md-3">
            <a href="<?= $model->url ?>" class="thumbnail">
                <?= Html::img( $model->photo->thumbnail ) ?>
            </a>
        </div>
        
        <div class="col-md-9">
            <h4>
                <a href="<?= $model->url ?>"><?= $model->title ?></a>
            </h4>
            
            <p>
              <?= $model->excerpt ?>
            </p>
            
            <p>
                <a class="btn btn-info" href="<?= $model->url ?>">Read more</a>
            </p>
            
            <ul class="list-inline">
                <li>
                    <i class="glyphicon glyphicon-user"></i> by <a href="<?= $model->user->profileurl ?>"><?= $model->user->fullname ?></a>
                </li>
                <li>
                    <i class="glyphicon glyphicon-calendar"></i> <?= $model->prettydatetimepublished ?>
                </li>
            <?php if (Yii::$app->user->isGuest === false && $model->user_id == Yii::$app->user->identity->id) : ?>
                <li>
                    <?= Html::a('', ['delete', 'id' => $model->id], [
                        'class' => 'glyphicon glyphicon-trash',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </li>
            <?php endif ?>    
            </ul>
        </div>
        
    </div>
    <?php endforeach ?>
    
    <?php
    // Pagination
    if ( $dataProvider->pagination ) {
        echo LinkPager::widget([
            'pagination' => $dataProvider->pagination,
        ]);
    }
    ?>