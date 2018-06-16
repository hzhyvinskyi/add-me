<?php

/* @var $this yii\web\View */
/* @var $users[] \frontend\models\User */

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Social networking service';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <?php foreach ($users as $user): ?>
			<a href="<?= Url::to(['/user/profile/view', 'nickname' => $user->getNickname()]) ?>">
				<?= Html::encode($user->username) ?>
			</a>
			<hr>
		<?php endforeach; ?>

    </div>
</div>
