<?php

/* @var $this \yii\web\View */
/* @var $post \frontend\models\Post */
/* @var $currentUser \frontend\models\User */

use yii\helpers\Html;
?>

<div class="post-default-index">

    <div class="row">

        <div class="col-md-12">
            <?php if ($post->user): ?>
                <h3><?= Html::encode($post->user->username) ?></h3>
            <?php endif; ?>
        </div>

        <div class="col-md-12">
            <img style="width:650px;" src="<?= Html::encode($post->getImage()) ?>">
        </div>

        <div class="col-md-12">
            <p><?= Html::encode($post->description) ?></p>
        </div>

    </div>

	<div class="row">

		<div class="col-md-12">
			<p>Likes: <span class="likes-count"><?= $post->countLikes() ?></span></p>
		</div>

	</div>

	<div class="row">

		<div class="col-md-12">

			<a href="#" class="btn-sm btn-primary button-like <?= ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : "" ?>" data-id="<?= $post->id ?>">Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span></a>

			<a href="#" class="btn-sm btn-primary button-unlike <?= ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none" ?>" data-id="<?= $post->id ?>">Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span></a>

		</div>

	</div>

</div>

<?php $this->registerJsFile('@web/js/likes.js', [
		'depends' => yii\web\JqueryAsset::className()
]); ?>
