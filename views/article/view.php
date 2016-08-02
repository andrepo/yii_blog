<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Article */

$showBreadCrumbs = ( isset($noBreadCrumbs) && $noBreadCrumbs ) ? false : true;
$this->title = $model->title;
if ( $showBreadCrumbs ) {
	//$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;
}
?>

    <h1>
        <a href=""><?= $model->title ?></a>
    </h1>
        
    <p class="lead">
		<i class="glyphicon glyphicon-user"></i> by <a href="<?= $model->user->profileurl ?>"><?= $model->user->fullname ?></a>
	</p>
	
	<ul class="list-inline">
		<li>
			<i class="glyphicon glyphicon-calendar"></i> Posted on <?= $model->prettydatetimepublished ?>
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
		<li>
			<a href="<?= $links['export'] ?>" target="_blank">
				<i class="glyphicon glyphicon-save-file"></i> PDF
			</a>
		</li>
	</ul>
		
    <hr>
        
    <?= Html::img( $model->photo->poster ) ?>
                
    <p class="lead">
        <?= $model->news ?>
    </p>
    