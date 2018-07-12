<?php

/* @var $this yii\web\View */
/* @var $currentUser \frontend\models\User */
/* @var $feedItems[] \frontend\models\Feed */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\JqueryAsset;

$this->title = 'Social networking service';

$this->registerMetaTag([
    'name' => 'description',
    'content' => 'Add me is a social networking service. Here you can create posts, share your pictures and thoughts with friends',
]);

$this->registerMetaTag([
    'name' => 'description',
    'keywords' => 'Add me, Social networking service, Social network, create posts, share pictures, read news',
]);
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
											<?= Yii::t('index', 'Like') ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
										</a>
										<a href="#" class="btn btn-default button-unlike <?= ($currentUser->likesPost($feedItem->post_id)) ? "" : "display-none" ?>" data-id="<?= $feedItem->post_id ?>">
                                            <?= Yii::t('index', 'Unlike') ?>&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-down"></span>
										</a>
									</div>
									<div class="post-comments">
										<a href="<?= Url::to(['/post/default/view', 'id' => $feedItem->post_id]) ?>">
											<?= Yii::t('index', 'Comments') ?>
											<?php if($feedItem->getPostCommentCount()): ?>
												(<?= $feedItem->getPostCommentCount() ?>)
											<?php else: ?>
												(0)
											<?php endif; ?>
										</a>
									</div>
									<div class="post-date">
									<span><?= Yii::$app->formatter->asDatetime($feedItem->post_created_at) ?>
</span>
									</div>
									<div class="post-report">
										<?php if (!$feedItem->isReported($currentUser)): ?>
											<a href="#" class="btn btn-default button-complain" data-id="<?= $feedItem->post_id ?>">
												<?= Yii::t('index', 'Report post') ?> <i class="fa fa-cog fa-spin fa-fw icon-preloader" style="display: none"></i>
											</a>
										<?php else: ?>
											<p><?= Yii::t('index', 'Post already reported') ?></p>
										<?php endif; ?>
									</div>
								</div>
							</article>
							<!-- feed item -->

						<?php endforeach; ?>
					<?php else: ?>
						<div class="col-md-12">
							<p><?= Yii::t('index', 'Nobody posted yet') ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(),
]);

$this->registerJsFile('@web/js/complaints.js', [
	'depends' => JqueryAsset::className(),
]);
?>
