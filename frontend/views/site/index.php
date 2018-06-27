<?php

/* @var $this yii\web\View */
/* @var $currentUser \frontend\models\User */
/* @var $feedItems[] \frontend\models\Feed */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = 'Social networking service';
?>
<div class="site-index">
	<?php if ($feedItems): ?>

		<?php foreach ($feedItems as $feedItem): ?>

			<div class="row">

				<div class="col-md-12">
					<p>
						<img src="<?= $feedItem->author_picture ?>" class="feed-profile-picture">
						<a href="<?= Url::to(['/user/profile/view', 'nickname' => $feedItem->author_nickname]) ?>">
                            <?= Html::encode($feedItem->author_name) ?>
						</a>&nbsp;&nbsp;
                        <?= Yii::$app->formatter->asDatetime($feedItem->post_created_at) ?>
					</p>
				</div>

				<div class="col-md-12">
					<img src="<?= Yii::$app->storage->getFile($feedItem->post_filename) ?>" class="post-picture">
				</div>


				<div class="col-md-12">
					<p>
						<?= nl2br(HtmlPurifier::process($feedItem->post_description)) ?>
					</p>
				</div>

				<div class="col-md-12">
					<p>
						Likes: <span class="likes-count"><?= $feedItem->countLikes() ?></span>
					</p>
				</div>

				<div class="col-md-12">
					<a href="#" class="btn-sm btn-primary button-like <?= ($currentUser->likesPost($feedItem->post_id)) ? "display-none" : "" ?>" data-id="<?= $feedItem->post_id ?>">
						Like&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
					</a>
					<a href="#" class="btn-sm btn-primary button-unlike <?= ($currentUser->likesPost($feedItem->post_id)) ? "" : "display-none" ?>" data-id="<?= $feedItem->post_id ?>">
						Unlike&nbsp;&nbsp;<span class="glyphicon glyphicon-thumbs-up"></span>
					</a>
				</div>

			</div>

			<hr>

		<?php endforeach; ?>

	<?php else: ?>
		<div class="row">

			<div class="col-md-12">
				<p>Nobody posted yet</p>
			</div>

		</div>
	<?php endif; ?>
</div>

<?php $this->registerJsFile('@web/js/likes.js', [
		'depends' => \yii\web\JqueryAsset::className(),
]);
