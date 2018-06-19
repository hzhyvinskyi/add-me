<?php

/* @var $this \yii\web\View */
/* @var $user \frontend\models\User */
/* @var $currentUser \frontend\models\User */
/* @var $modelPicture \frontend\models\User */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

$this->title = $user->username;
?>

<h3><?= Html::encode($user->username) ?></h3>
<p><?= HtmlPurifier::process($user->about) ?></p>

<hr>

<img src="<?= $user->getPicture() ?>">

<?= FileUpload::widget([
    'model' => $modelPicture,
    'attribute' => 'picture',
    'url' => ['/user/profile/upload-picture'],
    'options' => ['accept' => 'image/*'],
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
]); ?>

<hr>

<?php if ($currentUser && !$user->equals($currentUser)): ?>
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
				<div class="col-md-12">
					<a href="<?= Url::to(['/user/profile/view', 'nickname' => ($follower['nickname'] ? $follower['nickname'] : $follower['id'])]) ?>">
						<?= Html::encode($follower['username']) ?>
					</a>
					<br>
				</div>
			<?php endforeach; ?>
		</div>
		<hr>
	<?php endif; ?>
<?php endif; ?>

<?php if ($user->countSubscriptions()): ?>
	<!-- Button trigger subscriptions modal -->
	<button type="button" data-toggle="modal" data-target="#subsModal">
		following:<?= $user->countSubscriptions() ?>
	</button>
<?php endif; ?>

<?php if ($user->countFollowers()): ?>
	<!-- Button trigger followers modal -->
	<button type="button" data-toggle="modal" data-target="#follModal">
		followers:<?= $user->countFollowers() ?>
	</button>
<?php endif; ?>

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
