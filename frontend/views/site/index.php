<?php

/* @var $this yii\web\View */
/* @var $currentUser \frontend\models\User */
/* @var $feedItems[] \frontend\models\Feed */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = 'Social networking service';
?>

<div class="page-posts no-padding">
	<div class="row">
		<div class="page page-post col-sm-12 col-xs-12">
			<div class="blog-posts blog-posts-large">

				<div class="row">

					<?php if ($feedItems): ?>

						<?php foreach ($feedItems as $feedItem): ?>

							<!-- feed item -->
							<article class="post col-sm-12 col-xs-12">
								<div class="post-meta">
									<div class="post-title">
										<img src="<?= $feedItem->author_picture ?>" class="author-image" alt="author-picture">
										<div class="author-name">
											<a href="<?= Url::to(['/user/profile/view', 'nickname' => $feedItem->author_nickname]) ?>">
                                                <?= Html::encode($feedItem->author_name) ?>
											</a>
										</div>
									</div>
								</div>
								<div class="post-type-image">
									<a href="<?= Url::to(['/post/default/view', 'id' => $feedItem->post_id]) ?>">
										<img src="<?= Yii::$app->storage->getFile($feedItem->post_filename) ?>" class="post-picture" alt="post-image">
									</a>
								</div>
								<div class="post-description">
									<p>
                                    	<?= nl2br(HtmlPurifier::process($feedItem->post_description)) ?>
									</p>
								</div>
								<br>
								<div class="post-bottom">
									<div class="post-likes">
										<i class="fa fa-lg fa-heart-o"></i>
										<span class="likes-count"><?= $feedItem->countLikes() ?></span>
										&nbsp;&nbsp;&nbsp;
										<a href="#" class="btn btn-default button-like <?= ($currentUser->likesPost($feedItem->post_id)) ? "display-none" : "" ?>" data-id="<?= $feedItem->post_id ?>">
											Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
										</a>
										<a href="#" class="btn btn-default button-unlike <?= ($currentUser->likesPost($feedItem->post_id)) ? "" : "display-none" ?>" data-id="<?= $feedItem->post_id ?>">
											Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
										</a>
									</div>
									<div class="post-comments">
										<a href="<?= Url::to(['/post/default/view', 'id' => $feedItem->post_id]) ?>">
											<?= ($feedItem->getPostCommentCount()) ? $feedItem->getPostCommentCount() : '0' ?> <?= ($feedItem->getPostCommentCount() == '1') ? 'Comment' : 'Comments' ?>
										</a>
									</div>
									<div class="post-date">
										<span><?= Yii::$app->formatter->asDatetime($feedItem->post_created_at) ?>
</span>
									</div>
									<div class="post-report">
										<a href="#">Report post</a>
									</div>
								</div>
							</article>
							<!-- feed item -->

						<?php endforeach; ?>
					<?php else: ?>
							<div class="col-md-12">
								<p>Nobody posted yet</p>
							</div>
                    <?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->registerJsFile('@web/js/likes.js', [
		'depends' => \yii\web\JqueryAsset::className(),
]);
