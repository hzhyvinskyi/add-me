<?php

/* @var $this \yii\web\View */
/* @var $user \frontend\models\User */
/* @var $currentUser \frontend\models\User */
/* @var $modelPicture \frontend\models\User */
/* @var $currentUserPosts \frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

$this->title = $user->username;
?>
<div class="profile-page-index">
	<div class="alert alert-success display-none" id="profile-picture-success">Profile image has been updated</div>
	<div class="alert alert-danger display-none" id="profile-picture-fail"></div>

	<h3><?= Html::encode($user->username) ?></h3>
	<p><?= HtmlPurifier::process($user->about) ?></p>

	<hr>

	<img src="<?= $user->getPicture() ?>" id="profile-picture"/>

	<br>

    <?php if ($currentUser && $user->equals($currentUser)): ?>
		<br>
        <?= FileUpload::widget([
            'model' => $modelPicture,
            'attribute' => 'picture',
            'url' => ['/user/profile/upload-picture'],
            'options' => ['accept' => 'image/*'],
            'clientOptions' => [
                'maxFileSize' => 2000000
            ],
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
				if (data.result.success) {
					$("#profile-picture-success").show();
					$("#profile-picture-fail").hide();
					$("#profile-picture").attr("src", data.result.pictureUri);
				} else {
					$("#profile-picture-fail").html(data.result.errors.picture).show();
					$("#profile-picture-success").hide();
				}
			}',
            ],
        ]); ?>

		<a href="<?= Url::to(['/user/profile/delete-picture']) ?>" class="btn btn-danger">Delete picture</a>

    <?php endif; ?>

	<hr>

    <?php if ($currentUser && !$currentUser->equals($user)): ?>

        <?php if (!$currentUser->isFollowing($user)): ?>
			<a href="<?= Url::to(['/user/profile/subscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">
				Subscribe
			</a>
        <?php else: ?>
			<a href="<?= Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">
				Unsubscribe
			</a>
        <?php endif; ?>

		<hr>

        <?php if ($currentUser->getMutualSubscriptionsTo($user)): ?>
			<p>Friends, who are also following <?= Html::encode($user->username) ?>:</p>
			<div class="row">
                <?php foreach ($currentUser->getMutualSubscriptionsTo($user) as $follower): ?>
                    <?php if ($follower['id'] != $currentUser->getId()): ?>
						<div class="col-md-12">
							<a href="<?= Url::to(['/user/profile/view', 'nickname' => ($follower['nickname'] ? $follower['nickname'] : $follower['id'])]) ?>">
                                <?= Html::encode($follower['username']) ?>
							</a>
							<br>
						</div>
                    <?php endif; ?>
                <?php endforeach; ?>
			</div>
			<hr>
        <?php endif; ?>

    <?php endif; ?>

    <?php if ($user->countSubscriptions()): ?>
		<!-- Button trigger subscriptions modal -->
		<button type="button" class="btn btn-default" data-toggle="modal" data-target="#subsModal">
			following:<?= $user->countSubscriptions() ?>
		</button>
    <?php endif; ?>

    <?php if ($user->countFollowers()): ?>
		<!-- Button trigger followers modal -->
		<button type="button" class="btn btn-default" data-toggle="modal" data-target="#follModal">
			followers:<?= $user->countFollowers() ?>
		</button>
    <?php endif; ?>

	<hr>

	<?php if ($currentUser->equals($user) && $currentUserPosts): ?>
		<h4>My posts:</h4>

		<br>

		<?php foreach ($currentUserPosts as $post): ?>
			<img src="<?= Yii::$app->storage->getFile($post->filename) ?>" class="feed-profile-picture">
			<a href="<?= Url::to(['/post/default/view', 'id' => $post->getId()]) ?>">
				Post #<?= $post->getId() ?>&nbsp;&nbsp;
				<?= Yii::$app->formatter->asDatetime($post->created_at) ?>
				<hr>
			</a>
		<?php endforeach; ?>

	<?php endif; ?>
</div>

<!-- Subscriptions modal -->
<div class="modal fade" id="subsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Subscriptions</h4>
			</div>
			<div class="modal-body">
                <?php foreach ($user->getSubscriptions() as $subscription): ?>
					<a href="<?= Url::to(['/user/profile/view', 'nickname' => ($subscription['nickname'] ? $subscription['nickname'] : $subscription['id'])]) ?>">
                        <?= Html::encode($subscription['username']) ?>
					</a>
					<br>
                <?php endforeach; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Followers modal -->
<div class="modal fade" id="follModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Followers</h4>
			</div>
			<div class="modal-body">
				<?php foreach ($user->getFollowers() as $follower): ?>
					<a href="<?= Url::to(['/user/profile/view', 'nickname' => ($follower['nickname'] ? $follower['nickname'] : $follower['id'])]) ?>">
						<?= Html::encode($follower['username']) ?>
					</a>
					<br>
				<?php endforeach; ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
