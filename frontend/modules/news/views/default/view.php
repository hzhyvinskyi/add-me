<?php

/* @var $item \frontend\models\News */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use frontend\widgets\newsList\NewsList;
?>

<div class="row">
    <div class="col-md-8">
        <h3>
            <?= Html::encode($item->title) ?>
        </h3>
		<span><?= Yii::$app->formatter->asDatetime($item->created_at, 'medium') ?></span>
		<br>
		<img src="<?= $item->getPicture($item->picture) ?>">
		<br>
        <p>
            <?= HtmlPurifier::process($item->content) ?>
        </p>
		<br>
        <span>Author: <?= Html::encode($item->author) ?></span>
    </div>
    <div class="col-md-3 col-md-offset-1">
		<?= NewsList::widget(['showLimit' => 10]) ?>
    </div>
</div>
