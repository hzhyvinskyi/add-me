<?php

/* @var $this \yii\web\View */
/* @var $post \frontend\models\Post */
/* @var $currentUser \frontend\models\User */
/* @var $model \frontend\modules\post\models\forms\CommentForm */
/* @var $comments \frontend\models\Comment */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\bootstrap\ActiveForm;
?>

<div class="post-default-index">

    <div class="row">

        <div class="col-md-8">
            <?php if ($post->user): ?>
                <h3><?= Html::encode($post->user->username) ?></h3>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <img class="post-picture" src="<?= Html::encode($post->getImage()) ?>">
        </div>

        <div class="col-md-8">
            <p><?= nl2br(HtmlPurifier::process($post->description)) ?></p>
        </div>

    </div>

	<div class="row">

		<div class="col-md-8">
			<p>
				<span class="glyphicon glyphicon-heart-empty"></span> Likes: <span class="likes-count"><?= $post->countLikes() ?></span>
			</p>
		</div>

	</div>

	<div class="row">

		<div class="col-md-8">

			<a href="#" class="btn-sm btn-primary button-like <?= ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : "" ?>" data-id="<?= $post->getId() ?>">Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span></a>

			<a href="#" class="btn-sm btn-primary button-unlike <?= ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none" ?>" data-id="<?= $post->getId() ?>">Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span></a>

		</div>

	</div>

	<br>

	<div class="row">

		<div class="col-md-8">

			<?php if ($comments): ?>

				<h3>Comments:</h3>

				<?php foreach ($comments as $comment): ?>

					<div class="comment-block">

						<hr>

						<b><?= Html::encode($comment->user->username) ?></b>&nbsp;&nbsp;

						Created: <?= Yii::$app->formatter->asDatetime($comment->created_at) ?>&nbsp;&nbsp;

						<?php if ($comment->created_at !== $comment->updated_at): ?>

							|&nbsp; Updated: <?= Yii::$app->formatter->asDatetime($comment->updated_at) ?>&nbsp;&nbsp;

						<?php endif; ?>

						<?php if ($currentUser && $currentUser->getId() === $comment->user_id): ?>
							<a href="<?= Url::to(['update-comment', 'id' => $comment->getId()]) ?>">
								<span class="glyphicon glyphicon-edit"></span>
							</a>
						<?php endif; ?>

						<?php if ($currentUser && $currentUser->getId() === $post->user_id): ?>
							&nbsp;&nbsp;<a href="<?= Url::to(['delete-comment', 'id' => $comment->getId()]) ?>">
								<span class="glyphicon glyphicon-erase"></span>
							</a>
						<?php endif; ?>

						<br><br>

						<?= nl2br(Html::encode($comment->text)) ?>

						<hr>

					</div>

				<?php endforeach; ?>

			<?php endif; ?>

		</div>

	</div>

	<br>

	<div class="row">

		<div class="col-md-8">

			<?php if ($currentUser): ?>

				<?php $form = ActiveForm::begin() ?>
					<?= $form->field($model, 'text')->textarea(['rows' => 8])->label('Leave a comment') ?>
					<?= Html::submitButton('Comment on this post', ['class' => 'btn btn-success']) ?>
				<?php ActiveForm::end() ?>

			<?php else: ?>
				<p>Only <a href="<?= Url::to(['/user/default/login']) ?>">authorized</a> users can leave comments</p>
			<?php endif; ?>

		</div>

	</div>

</div>

<?php $this->registerJsFile('@web/js/likes.js', [
		'depends' => yii\web\JqueryAsset::className()
]); ?>
