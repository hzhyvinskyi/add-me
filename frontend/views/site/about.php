<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
?>
<div class="site-about">
	<h1>Add me <?= Yii::t('about', 'is the resource-community online for photo sharing'); ?></h1>

	<p>
		<?= Yii::t('about', 'The closest example is the social network') ?> <a href="https://www.instagram.com" target="_blank">Instagram</a>
	</p>

	<p><?= Yii::t('about', 'The purpose of the resource is to maintain photo diaries online, the opportunity to make friends and make acquaintances, share photos and follow the news of friends') ?></p>

	<p><?= Yii::t('about', 'Main features:') ?></p>

	<ul>
		<li><?= Yii::t('about', 'Creation of your own page (profile) that is public or visible only to a certain circle of people') ?></li>
		<li><?= Yii::t('about', 'Profile editing and settings page') ?></li>
		<li><?= Yii::t('about', 'Uploading and publishing photos to your profile') ?></li>
		<li><?= Yii::t('about', 'The ability to make friends and subscribe to other members of the network') ?></li>
		<li><?= Yii::t('about', 'An update tape with the ability to comment and put on the likes') ?></li>
		<li><?= Yii::t('about', 'Registration on the site. The ability to register via') ?> <a href="https://google.com" target="_blank">Google</a>, <a href="https://facebook.com" target="_blank">Facebook</a></li>
		<li><?= Yii::t('about', 'Alerts page: likes and subscriptions to user profile') ?></li>
		<li><?= Yii::t('about', 'Pages with lists of subscriptions and subscribers') ?></li>
		<li><?= Yii::t('about', 'Search by news') ?></li>
		<li><?= Yii::t('about', 'Possibility to send complaints to publications of other participants') ?></li>
		<li><?= Yii::t('about', 'Administrative panel for full control') ?></li>
	</ul>

</div>
