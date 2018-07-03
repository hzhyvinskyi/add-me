<?php

/* @var $this \yii\web\View */
/* @var $post \frontend\models\Post */
/* @var $currentUser \frontend\models\User */
/* @var $model \frontend\modules\post\models\forms\CommentForm */
/* @var $comments \frontend\models\Comment */
/* @var $commentCount \frontend\models\Feed */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\bootstrap\ActiveForm;
use frontend\widgets\newsList\NewsList;
?>

<div class="page-posts no-padding">
	<div class="row">
		<div class="page page-post col-sm-8 col-xs-8 post-82">

			<div class="blog-posts blog-posts-large">

				<div class="row">

					<!-- feed item -->
					<article class="post col-sm-18 col-xs-18">
						<div class="post-meta">
							<div class="post-title">
								<img src="<?= $post->user->getPicture() ?>" class="author-image">
								<div class="author-name">
									<a href="<?= Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]) ?>"><?= Html::encode($post->user->username) ?></a>
								</div>
							</div>
						</div>
						<div class="post-type-image">
							<img class="post-picture" src="<?= Html::encode($post->getImage()) ?>">
						</div>
                        <?php if ($post->description): ?>
							<div class="post-description">
								<p><?= nl2br(HtmlPurifier::process($post->description)) ?></p>
							</div>
                        <?php endif; ?>
						<br>
						<div class="post-bottom">
							<div class="post-likes">
								<i class="fa fa-lg fa-heart-o"></i>
								<span class="likes-count"><?= $post->countLikes() ?></span>
								&nbsp;&nbsp;&nbsp;
								<a href="#" class="btn btn-default button-like <?= ($currentUser->likesPost($post->getId())) ? "display-none" : "" ?>" data-id="<?= $post->getId() ?>">
									Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
								</a>
								<a href="#" class="btn btn-default button-unlike <?= ($currentUser->likesPost($post->getId())) ? "" : "display-none" ?>" data-id="<?= $post->getId() ?>">
									Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
								</a>
							</div>
							<div class="post-date date-single">
								<span><?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>
							</div>
							<div class="post-report">
								<button class="btn btn-default btn-report">Report post</button>
							</div>
						</div>
					</article>
					<!-- feed item -->

				</div>
				<div class="row">

					<div class="col">

                        <?php if ($comments): ?>

							<p class="fs-18">
                                <?= (count($comments)) ?>
                                <?= (count($comments) == '1') ? 'comment' : 'comments' ?>
							</p>

                            <?php foreach ($comments as $comment): ?>

								<div class="comment">

									<hr>

									<img src="<?= $comment->user->getPicture() ?>" class="author-image" alt="avatar">

									<a href="<?= Url::to(['/user/profile/view', 'nickname' => $comment->user->getNickname()]) ?>" class="author">
                                        <?= Html::encode($comment->user->username) ?>
									</a>&nbsp;&nbsp;&nbsp;

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

									<div class="post-comment-description">
                                        <?= nl2br(Html::encode($comment->text)) ?>
									</div>

									<hr>

								</div>

                            <?php endforeach; ?>

                        <?php endif; ?>

					</div>

				</div>

				<br>

				<div class="row">

					<div class="col">

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
		</div>

		<div class="col-md-3 col-md-offset-1">
            <?= NewsList::widget(['showLimit' => 15]) ?>
		</div>

	</div>
</div>

<?php $this->registerJsFile('@web/js/likes.js', [
    'depends' => yii\web\JqueryAsset::className()
]); ?>
