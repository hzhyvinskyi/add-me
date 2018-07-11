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

<div class="page-posts no-padding">
	<div class="row">
		<div class="page page-post col-sm-12 col-xs-12 post-82">

			<div class="blog-posts blog-posts-large">

				<div class="row">

					<!-- profile -->
					<article class="profile col-sm-12 col-xs-12">
						<div class="alert alert-success display-none" id="profile-picture-success">
							<?= Yii::t('profile_view', 'Profile image has been updated') ?>
						</div>
						<div class="alert alert-danger display-none" id="profile-picture-fail"></div>
						<div class="profile-title">
							<div class="author-name"><?= Html::encode($user->username) ?></div>
							<br>
							<img src="<?= $user->getPicture() ?>" id="profile-picture">

                            <?php if ($currentUser && !$currentUser->equals($user)): ?>
								<br><br>
                                <?php if (!$currentUser->isFollowing($user)): ?>
									<a href="<?= Url::to(['/user/profile/subscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">
										<?= Yii::t('profile_view', 'Subscribe') ?>
									</a>
                                <?php else: ?>
									<a href="<?= Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]) ?>" class="btn btn-info">
										<?= Yii::t('profile_view', 'Unsubscribe') ?>
									</a>
                                <?php endif; ?>

								<hr>

                                <?php if ($currentUser->getMutualSubscriptionsTo($user)): ?>
									<p>
                                        <?= Yii::t('profile_view', 'Friends, who are also following ') . Html::encode($user->username) ?>:
									</p>
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

								<a href="<?= Url::to(['/user/profile/delete-picture']) ?>" class="btn btn-danger">
									<?= Yii::t('profile_view', 'Delete picture') ?>
								</a>
								<a href="<?= Url::to(['/user/profile/update']) ?>" class="btn btn-default">
									<?= Yii::t('profile_view', 'Edit profile') ?>
								</a>
                            <?php endif; ?>

						</div>

						<div class="row">
							<div class="col-md-4">
                                <?php if ($user->about): ?>
									<br>
									<div class="profile-description">
                                        <?= nl2br(HtmlPurifier::process($user->about)) ?>
									</div>
                                <?php endif; ?>

								<br><br>
							</div>
						</div>

						<div class="profile-bottom">
							<div class="profile-post-count">
								<span><?= ($user->getPostCount()) ?>
                                    <?= ($user->getPostCount() == '1') ? Yii::t('profile_view', 'post') : Yii::t('profile_view', 'posts') ?>
								</span>
							</div>
							<div class="profile-followers">
								<a href="#" data-toggle="modal" data-target="#subsModal">
                                    <?= $user->countSubscriptions() . ' ' . Yii::t('profile_view', 'followers') ?>
								</a>
							</div>
							<div class="profile-following">
								<a href="#" data-toggle="modal" data-target="#follModal">
                                    <?= $user->countFollowers() . ' ' . Yii::t('profile_view', 'following') ?>
								</a>
							</div>
						</div>

						<br>

						<div class="row profile-posts">
                            <?php foreach ($user->getPosts() as $post): ?>
								<div class="col-md-4 profile-post">
									<a href="<?= Url::to(['/post/default/view', 'id' => $post->getId()]) ?>">
										<img src="<?= Yii::$app->storage->getFile($post->filename) ?>" class="author-image">
									</a>
								</div>
                            <?php endforeach; ?>
						</div>

					</article>

				</div>

			</div>
		</div>

	</div>
</div>

<!-- Subscriptions modal -->
<div class="modal fade" id="subsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><?= Yii::t('profile_view', 'Subscriptions') ?></h4>
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
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?= Yii::t('profile_view', 'Close') ?>
					</button>
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
				<h4 class="modal-title" id="myModalLabel"><?= Yii::t('profile_view', 'Followers') ?></h4>
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
				<button type="button" class="btn btn-default" data-dismiss="modal">
					<?= Yii::t('profile_view', 'Close') ?>
				</button>
			</div>
		</div>
	</div>
</div>
