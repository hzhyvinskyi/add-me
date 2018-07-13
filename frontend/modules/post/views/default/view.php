<?php

/* @var $this \yii\web\View */
/* @var $post \frontend\models\Post */
/* @var $currentUser \frontend\models\User */
/* @var $model \frontend\modules\post\models\forms\CommentForm */
/* @var $comments \frontend\models\Comment */
/* @var $commentCount \frontend\models\Feed */
/* @var $viewCount \frontend\models\Post */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\bootstrap\ActiveForm;
use frontend\widgets\newsList\NewsList;
use yii\web\JqueryAsset;

$this->title = 'Post #' . $post->getId();
?>

<div class="page-posts no-padding">
	<div class="row">
		<div class="page page-post col-sm-8 col-xs-12">

			<div class="blog-posts blog-posts-large">

				<div class="row">

					<!-- feed item -->
					<article class="post col-xs-12">
						<div class="post-meta">
							<div class="post-title">
								<img src="<?= $post->user->getPicture() ?>" class="author-image">
								<div class="author-name">
									<a href="<?= Url::to(['/user/profile/view', 'nickname' => $post->user->getNickname()]) ?>"><?= Html::encode($post->user->username) ?></a>
								</div>
								<div class="post__counter">
									<i class="glyphicon glyphicon-user"></i> <?= $post->viewCountByUser($currentUser) ?>&nbsp;&nbsp;
									<i class="glyphicon glyphicon-eye-open"></i> <?= $post->viewCount() ?>
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
                                    <?= Yii::t('post_view', 'Like') ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
								</a>
								<a href="#" class="btn btn-default button-unlike <?= ($currentUser->likesPost($post->getId())) ? "" : "display-none" ?>" data-id="<?= $post->getId() ?>">
                                    <?= Yii::t('post_view', 'Unlike') ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
								</a>
							</div>
							<div class="post-date date-single">
								<span><?= Yii::$app->formatter->asDatetime($post->created_at) ?></span>
							</div>
							<div class="post-report">
                                <?php if (!$post->isReported($currentUser)): ?>
									<a href="#" class="btn btn-default button-complain" data-id="<?= $post->getId() ?>">
                                        <?= Yii::t('post_view', 'Report post') ?> <i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display: none"></i>
									</a>
                                <?php else: ?>
									<p><?= Yii::t('post_view', 'Post already reported') ?></p>
                                <?php endif; ?>
							</div>
						</div>
					</article>
					<!-- feed item -->

				</div>
				<div class="row">

					<div class="col-md-12">

                        <?php if ($comments): ?>

							<p class="fs-18">
                                <?= (count($comments)) ?>
                                <?= (count($comments) == '1') ? Yii::t('post_view', 'comment') : Yii::t('post_view', 'comments') ?>
							</p>

                            <?php foreach ($comments as $comment): ?>

								<div class="comment">

									<hr>

									<img src="<?= $comment->user->getPicture() ?>" class="author-image" alt="avatar">

									<a href="<?= Url::to(['/user/profile/view', 'nickname' => $comment->user->getNickname()]) ?>" class="author">
                                        <?= Html::encode($comment->user->username) ?>
									</a>&nbsp;&nbsp;&nbsp;

									<?= Yii::t('post_view', 'Created:') ?>
									<?= Yii::$app->formatter->asDatetime($comment->created_at) ?>&nbsp;&nbsp;

                                    <?php if ($comment->created_at !== $comment->updated_at): ?>
										|&nbsp;
										<?= Yii::t('post_view', 'Updated:') ?>
										<?= Yii::$app->formatter->asDatetime($comment->updated_at) ?>&nbsp;&nbsp;
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

					<div class="col-md-12">

                        <?php if ($currentUser): ?>

                            <?php $form = ActiveForm::begin() ?>
                            <?= $form->field($model, 'text')->textarea(['rows' => 8])->label(Yii::t('post_view', 'Leave a comment')) ?>
                            <?= Html::submitButton(Yii::t('post_view', 'Comment on this post'), ['class' => 'btn btn-success']) ?>
                            <?php ActiveForm::end() ?>

                        <?php else: ?>
							<p>Only <a href="<?= Url::to(['/user/default/login']) ?>">authorized</a> users can leave comments</p>
                        <?php endif; ?>

					</div>

				</div>

			</div>
		</div>

		<div class="col-xs-12 col-sm-3 col-sm-offset-1">
            <?= NewsList::widget(['showLimit' => 15]) ?>
		</div>

	</div>
</div>

<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className()
]);

$this->registerJsFile('@web/js/complaints.js', [
    'depends' => JqueryAsset::className(),
]);
?>
