<?php

/* @var $this \yii\web\View */
/* @var $user \frontend\models\User */
/* @var $currentUser \frontend\models\User */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

$this->title = $user->username;
?>

<h3><?= Html::encode($user->username) ?></h3>
<p><?= HtmlPurifier::process($user->about) ?></p>

<hr>

<a href="<?= Url::to(['/user/profile/subscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">
	Subscribe
</a>
<a href="<?= Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">
	Unsubscribe
</a>

<hr>

<?php if ($currentUser && $currentUser->getMutualSubscriptionsTo($user) && $currentUser->id !== $user->id): ?>
	<p>Friends, who are also following <?= Html::encode($user->username) ?>:</p>
	<div class="row">
		<?php foreach ($currentUser->getMutualSubscriptionsTo($user) as $follower): ?>
			<?php if ($currentUser->username !== $follower['username']): ?>
				<div class="col-md-12">
					<?= Html::encode($follower['username']) ?>
					<br>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
	<hr>
<?php endif; ?>

<!-- Button trigger subscriptions modal -->
<button type="button" data-toggle="modal" data-target="#subsModal">
	following:<?= $user->countSubscriptions() ?>
</button>

<!-- Button trigger followers modal -->
<button type="button" data-toggle="modal" data-target="#follModal">
	followers:<?= $user->countFollowers() ?>
</button>

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